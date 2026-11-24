<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LaporanController as AdminLaporan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\LaporanService;
use Illuminate\Http\Request;

class IkhtisarController extends Controller
{
    public function index()
    {
        $omzet = Pembayaran::where('status_verifikasi', 'terverifikasi')
            ->whereMonth('updated_at', now()->month)
            ->sum('jumlah');
        $pesanan = Pesanan::whereMonth('tanggal_pesan', now()->month)->count();
        $menunggu = Pembayaran::where('status_verifikasi', 'menunggu')->count();
        $selesai = Pesanan::where('status_pesanan', 'selesai')->whereMonth('tanggal_pesan', now()->month)->count();

        return view('pengelola.dashboard', compact('omzet', 'pesanan', 'menunggu', 'selesai'));
    }

    public function laporan(Request $request, LaporanService $service, AdminLaporan $laporan)
    {
        return view('laporan.index', $laporan->siapkan($request, $service, false));
    }

    public function transaksi(Request $request)
    {
        $transaksi = Pembayaran::with(['pesanan.pelanggan'])
            ->when($request->filled('status'), fn ($q) => $q->where('status_verifikasi', $request->string('status')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $cari = '%'.$request->string('q').'%';
                $q->whereHas('pesanan', fn ($p) => $p->where('no_pesanan', 'like', $cari));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pengelola.transaksi', compact('transaksi'));
    }
}
