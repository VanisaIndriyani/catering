<?php

namespace App\Http\Controllers\Koki;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        $pesanan = Pesanan::with(['pelanggan', 'pembayaran', 'detail.menu'])
            ->whereIn('status_pesanan', ['diterima', 'diproses', 'selesai'])
            ->when($request->filled('produksi'), fn ($q) => $q->where('status_produksi', $request->string('produksi')))
            ->orderBy('tanggal_kebutuhan')
            ->paginate(10)
            ->withQueryString();

        $ringkas = [
            'menunggu' => Pesanan::whereIn('status_pesanan', ['diterima', 'diproses'])->where('status_produksi', 'menunggu')->count(),
            'dimasak' => Pesanan::where('status_produksi', 'dimasak')->count(),
            'siap' => Pesanan::where('status_produksi', 'siap')->count(),
            'selesai' => Pesanan::whereIn('status_pesanan', ['diterima', 'diproses', 'selesai'])->where('status_produksi', 'selesai')->count(),
        ];

        return view('koki.pesanan.index', compact('pesanan', 'ringkas'));
    }

    public function show(Pesanan $pesanan)
    {
        abort_unless(in_array($pesanan->status_pesanan, ['diterima', 'diproses', 'selesai'], true), 404);
        $pesanan->load(['pelanggan', 'detail.menu', 'pembayaran']);

        return view('koki.pesanan.show', compact('pesanan'));
    }

    public function status(Request $request, Pesanan $pesanan)
    {
        if (! in_array($pesanan->status_pesanan, ['diterima', 'diproses', 'selesai'], true)) {
            return back()->with('error', 'Pesanan ini belum diterima admin.');
        }

        $data = $request->validate([
            'status_produksi' => ['required', Rule::in(['menunggu', 'dimasak', 'siap', 'selesai'])],
        ]);

        $statusPesanan = $pesanan->status_pesanan;
        if (in_array($data['status_produksi'], ['dimasak', 'siap'], true) && $statusPesanan === 'diterima') {
            $statusPesanan = 'diproses';
        }
        if ($data['status_produksi'] === 'selesai') {
            $statusPesanan = 'selesai';
        }

        $pesanan->update([
            'status_produksi' => $data['status_produksi'],
            'status_pesanan' => $statusPesanan,
        ]);

        return back()->with('success', 'Status produksi diperbarui.');
    }
}
