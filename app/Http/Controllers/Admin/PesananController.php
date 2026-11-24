<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $pesanan = Pesanan::with(['pelanggan', 'pembayaran'])
            ->when($request->filled('status'), fn ($q) => $q->where('status_pesanan', $request->string('status')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $cari = '%'.$request->string('q').'%';
                $q->where(function ($inner) use ($cari) {
                    $inner->where('no_pesanan', 'like', $cari)
                        ->orWhereHas('pelanggan', fn ($p) => $p->where('nama', 'like', $cari));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['pelanggan.pengguna', 'detail.menu', 'pembayaran']);

        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function terima(Pesanan $pesanan)
    {
        if ($pesanan->status_pesanan !== 'menunggu') {
            return back()->with('error', 'Hanya pesanan yang menunggu konfirmasi yang bisa diterima.');
        }

        $pesanan->update([
            'status_pesanan' => 'diterima',
            'catatan_status' => null,
        ]);

        return back()->with('success', 'Pesanan '.$pesanan->no_pesanan.' diterima.');
    }

    public function tolak(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->status_pesanan !== 'menunggu') {
            return back()->with('error', 'Hanya pesanan yang menunggu konfirmasi yang bisa ditolak.');
        }

        $data = $request->validate([
            'alasan' => ['nullable', 'string', 'max:300'],
        ]);

        $pesanan->update([
            'status_pesanan' => 'ditolak',
            'catatan_status' => $data['alasan'] ?? 'Ditolak oleh admin.',
        ]);

        return back()->with('success', 'Pesanan ditolak.');
    }

    public function status(Request $request, Pesanan $pesanan)
    {
        $data = $request->validate([
            'status_pesanan' => ['required', Rule::in(['menunggu', 'diterima', 'ditolak', 'diproses', 'selesai', 'dibatalkan'])],
            'catatan_status' => ['nullable', 'string', 'max:300'],
        ]);

        $pesanan->update($data);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
