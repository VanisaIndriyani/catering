<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $pelanggan = Pelanggan::with('pengguna')
            ->when($request->filled('q'), function ($q) use ($request) {
                $cari = '%'.$request->string('q').'%';
                $q->where(function ($inner) use ($cari) {
                    $inner->where('nama', 'like', $cari)
                        ->orWhere('email', 'like', $cari)
                        ->orWhere('no_telepon', 'like', $cari);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['pengguna', 'pesanan' => fn ($q) => $q->latest()]);

        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $user = $pelanggan->pengguna;

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('pengguna', 'email')->ignore($user->id_pengguna, 'id_pengguna')],
            'no_telepon' => ['required', 'string', 'max:20'],
            'alamat_instansi' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->fill([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_telepon' => $data['no_telepon'],
        ]);
        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        $pelanggan->update([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'no_telepon' => $data['no_telepon'],
            'alamat_instansi' => $data['alamat_instansi'] ?? null,
        ]);

        return back()->with('success', 'Data pelanggan diperbarui.');
    }
}
