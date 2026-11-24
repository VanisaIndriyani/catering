<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $pembayaran = Pembayaran::with(['pesanan.pelanggan'])
            ->when($request->filled('status'), fn ($q) => $q->where('status_verifikasi', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        if (in_array($pembayaran->metode, ['transfer', 'qris'], true) && ! $pembayaran->bukti_bayar) {
            return back()->with('error', 'Bukti pembayaran belum diunggah pelanggan.');
        }

        $pembayaran->update([
            'status_verifikasi' => 'terverifikasi',
            'tanggal_bayar' => $pembayaran->tanggal_bayar ?? today()->toDateString(),
            'catatan_admin' => $request->input('catatan_admin'),
        ]);

        return back()->with('success', 'Pembayaran tercatat dan terverifikasi.');
    }

    public function tolak(Request $request, Pembayaran $pembayaran)
    {
        $data = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:300'],
        ]);

        $pembayaran->update([
            'status_verifikasi' => 'ditolak',
            'catatan_admin' => $data['catatan_admin'],
        ]);

        return back()->with('success', 'Bukti pembayaran ditolak. Pelanggan dapat mengunggah ulang.');
    }
}
