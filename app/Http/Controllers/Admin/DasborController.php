<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Support\Status;

class DasborController extends Controller
{
    public function index()
    {
        $menunggu = Pesanan::where('status_pesanan', 'menunggu')->count();
        $bayar = Pembayaran::where('status_verifikasi', 'menunggu')->count();
        $pelanggan = Pelanggan::count();
        $omzet = Pembayaran::where('status_verifikasi', 'terverifikasi')
            ->whereMonth('updated_at', now()->month)
            ->sum('jumlah');
        $terbaru = Pesanan::with(['pelanggan', 'pembayaran'])->latest()->take(6)->get();
        $menu = Menu::where('status', 'aktif')->count();

        $mulai = now()->startOfDay()->subDays(6);
        $hitungHari = Pesanan::query()
            ->where('created_at', '>=', $mulai)
            ->selectRaw('date(created_at) as hari, count(*) as jumlah')
            ->groupByRaw('date(created_at)')
            ->pluck('jumlah', 'hari');
        $harian = collect(range(0, 6))->map(function (int $i) use ($mulai, $hitungHari) {
            $hari = $mulai->copy()->addDays($i);

            return [
                'label' => $hari->translatedFormat('D'),
                'tanggal' => $hari->format('j'),
                'jumlah' => (int) ($hitungHari[$hari->toDateString()] ?? 0),
            ];
        });
        $puncak = max(1, (int) $harian->max('jumlah'));

        $hitungStatus = Pesanan::query()
            ->selectRaw('status_pesanan as status, count(*) as jumlah')
            ->groupBy('status_pesanan')
            ->pluck('jumlah', 'status');
        $status = $this->irisan(array_map(
            fn (string $kunci) => ['kunci' => $kunci, 'jumlah' => (int) ($hitungStatus[$kunci] ?? 0)],
            array_keys(Status::MAP['pesanan'])
        ), [
            'menunggu' => '#d4af37',
            'diterima' => '#3d7a57',
            'diproses' => '#1f6b45',
            'selesai' => '#14532d',
            'ditolak' => '#c4473a',
            'dibatalkan' => '#8d3a32',
        ]);

        $hitungBayar = Pembayaran::query()
            ->selectRaw('status_verifikasi as status, count(*) as jumlah')
            ->groupBy('status_verifikasi')
            ->pluck('jumlah', 'status');
        $pembayaran = collect(Status::MAP['bayar'])->map(function (array $meta, string $kunci) use ($hitungBayar) {
            return [
                'label' => $meta[0],
                'jumlah' => (int) ($hitungBayar[$kunci] ?? 0),
                'warna' => ['wait' => '#d4af37', 'ok' => '#14532d', 'bad' => '#c4473a'][$meta[1]] ?? '#14532d',
            ];
        })->values();
        $totalBayar = max(1, (int) $pembayaran->sum('jumlah'));

        return view('admin.dashboard', compact(
            'menunggu', 'bayar', 'pelanggan', 'omzet', 'terbaru', 'menu',
            'harian', 'puncak', 'status', 'pembayaran', 'totalBayar'
        ));
    }

    private function irisan(array $pasangan, array $warna): array
    {
        $total = array_sum(array_column($pasangan, 'jumlah'));
        $keliling = 2 * M_PI * 42;
        $jalan = 0.0;
        $iris = [];

        foreach ($pasangan as $item) {
            $panjang = $total > 0 ? ($item['jumlah'] / $total) * $keliling : 0;
            if ($panjang > 0) {
                $iris[] = [
                    'kunci' => $item['kunci'],
                    'label' => Status::label('pesanan', $item['kunci']),
                    'jumlah' => $item['jumlah'],
                    'warna' => $warna[$item['kunci']] ?? '#14532d',
                    'panjang' => round($panjang, 2),
                    'sisa' => round(max($keliling - $panjang, 0), 2),
                    'geser' => round(-$jalan, 2),
                ];
            }
            $jalan += $panjang;
        }

        return ['total' => $total, 'iris' => $iris];
    }
}
