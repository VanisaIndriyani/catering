<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LaporanService
{
    public function susun(string $jenis, Carbon $awal, Carbon $akhir): array
    {
        if ($jenis === 'transaksi') {
            $rows = Pembayaran::with(['pesanan.pelanggan'])
                ->whereDate('created_at', '>=', $awal->toDateString())
                ->whereDate('created_at', '<=', $akhir->toDateString())
                ->latest()
                ->get();

            return [
                'jenis' => 'transaksi',
                'rows' => $rows,
                'jumlah' => $rows->count(),
                'total' => (float) $rows->where('status_verifikasi', 'terverifikasi')->sum('jumlah'),
                'kelompok' => $rows->groupBy('status_verifikasi')->map(fn (Collection $item) => $item->count())->all(),
            ];
        }

        $rows = Pesanan::with(['pelanggan', 'pembayaran'])
            ->whereDate('tanggal_pesan', '>=', $awal->toDateString())
            ->whereDate('tanggal_pesan', '<=', $akhir->toDateString())
            ->latest('tanggal_pesan')
            ->get();

        return [
            'jenis' => 'pesanan',
            'rows' => $rows,
            'jumlah' => $rows->count(),
            'total' => (float) $rows->sum('total_harga'),
            'kelompok' => $rows->groupBy('status_pesanan')->map(fn (Collection $item) => $item->count())->all(),
        ];
    }
}
