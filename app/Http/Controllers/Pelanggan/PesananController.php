<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller
{
    public function buat()
    {
        [$baris, $total] = $this->isiKeranjang();

        return view('pelanggan.pesanan.buat', compact('baris', 'total'));
    }

    public function simpan(Request $request)
    {
        $pelanggan = $request->user()->pelanggan;
        abort_unless($pelanggan, 403);

        $data = $request->validate([
            'tanggal_kebutuhan' => ['required', 'date', 'after_or_equal:today'],
            'catatan' => ['nullable', 'string', 'max:500'],
            'bukti' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        [$baris, $total] = $this->isiKeranjang();
        if ($baris === []) {
            return redirect()->route('pelanggan.menu.index')->with('error', 'Keranjang masih kosong. Pilih menu terlebih dahulu.');
        }

        foreach ($baris as $barisItem) {
            if (! $barisItem['menu']->tersedia()) {
                return back()->with('error', $barisItem['menu']->nama_menu.' sudah tidak tersedia. Hapus dari keranjang untuk melanjutkan.')->withInput();
            }
        }

        $path = $request->file('bukti')->store('bukti', 'public');

        try {
            $pesanan = DB::transaction(function () use ($pelanggan, $data, $baris, $path) {
                $urut = Pesanan::whereDate('created_at', today())->count() + 1;
                $nomor = 'CC-'.now()->format('Ymd').'-'.str_pad((string) $urut, 3, '0', STR_PAD_LEFT);

                $pesanan = Pesanan::create([
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'no_pesanan' => $nomor,
                    'tanggal_pesan' => today()->toDateString(),
                    'tanggal_kebutuhan' => $data['tanggal_kebutuhan'],
                    'catatan' => $data['catatan'] ?? null,
                    'total_harga' => 0,
                    'status_pesanan' => 'menunggu',
                    'status_produksi' => 'menunggu',
                ]);

                foreach ($baris as $item) {
                    DetailPesanan::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'id_menu' => $item['menu']->id_menu,
                        'jumlah_porsi' => $item['jumlah'],
                        'harga_satuan' => $item['menu']->harga,
                    ]);
                }

                $pesanan->update(['total_harga' => $pesanan->hitungTotal()]);

                Pembayaran::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'jumlah' => $pesanan->total_harga,
                    'tanggal_bayar' => today()->toDateString(),
                    'metode' => 'transfer',
                    'bukti_bayar' => $path,
                    'status_verifikasi' => 'menunggu',
                ]);

                return $pesanan;
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }

        session()->forget('cart');

        return redirect()
            ->route('pelanggan.pesanan.show', $pesanan)
            ->with('success', 'Pesanan '.$pesanan->no_pesanan.' tersimpan. Bukti transfer sudah diunggah. Total '.rupiah($pesanan->total_harga).'.');
    }

    public function index(Request $request)
    {
        $pelanggan = $request->user()->pelanggan;
        abort_unless($pelanggan, 403);

        $pesanan = Pesanan::with('pembayaran')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->whereIn('status_pesanan', ['menunggu', 'diterima', 'diproses'])
            ->latest()
            ->paginate(8);

        return view('pelanggan.pesanan.index', [
            'pesanan' => $pesanan,
            'judul' => 'Lacak Pesanan',
            'keterangan' => 'Pantau pesanan yang masih berjalan.',
        ]);
    }

    public function riwayat(Request $request)
    {
        $pelanggan = $request->user()->pelanggan;
        abort_unless($pelanggan, 403);

        $pesanan = Pesanan::with('pembayaran')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->whereIn('status_pesanan', ['selesai', 'ditolak', 'dibatalkan'])
            ->latest()
            ->paginate(8);

        return view('pelanggan.pesanan.index', [
            'pesanan' => $pesanan,
            'judul' => 'Riwayat Pesanan',
            'keterangan' => 'Pesanan yang sudah selesai, ditolak, atau dibatalkan.',
        ]);
    }

    public function show(Request $request, Pesanan $pesanan)
    {
        $this->milik($request, $pesanan);
        $pesanan->load(['detail.menu', 'pembayaran', 'pelanggan']);

        return view('pelanggan.pesanan.show', compact('pesanan'));
    }

    public function bukti(Request $request, Pesanan $pesanan)
    {
        $this->milik($request, $pesanan);
        abort_if(in_array($pesanan->status_pesanan, ['ditolak', 'dibatalkan'], true), 403);

        $request->validate([
            'bukti' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $path = $request->file('bukti')->store('bukti', 'public');
        $pesanan->pembayaran->update([
            'bukti_bayar' => $path,
            'tanggal_bayar' => today()->toDateString(),
            'status_verifikasi' => 'menunggu',
            'catatan_admin' => null,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function batal(Request $request, Pesanan $pesanan)
    {
        $this->milik($request, $pesanan);

        if ($pesanan->status_pesanan !== 'menunggu') {
            return back()->with('error', 'Pesanan yang sudah dikonfirmasi tidak bisa dibatalkan dari sini.');
        }

        $pesanan->update([
            'status_pesanan' => 'dibatalkan',
            'catatan_status' => 'Dibatalkan oleh pelanggan.',
        ]);

        return back()->with('success', 'Pesanan dibatalkan.');
    }

    private function milik(Request $request, Pesanan $pesanan): void
    {
        $pelanggan = $request->user()->pelanggan;
        abort_unless($pelanggan && $pesanan->id_pelanggan === $pelanggan->id_pelanggan, 403);
    }

    private function isiKeranjang(): array
    {
        $cart = session('cart', []);
        if ($cart === []) {
            return [[], 0];
        }

        $menus = Menu::with('kategori')->whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');
        $baris = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $menu = $menus->get($id);
            if (! $menu) {
                continue;
            }
            $jumlah = max(1, (int) ($item['jumlah'] ?? 1));
            $sub = round($jumlah * (float) $menu->harga, 2);
            $total += $sub;
            $baris[] = [
                'menu' => $menu,
                'jumlah' => $jumlah,
                'sub' => $sub,
            ];
        }

        return [$baris, $total];
    }
}
