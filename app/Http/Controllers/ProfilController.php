<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function edit(Request $request)
    {
        return view('profil.edit', [
            'user' => $request->user()->load('pelanggan'),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('pengguna', 'email')->ignore($user->id_pengguna, 'id_pengguna')],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('pengguna', 'username')->ignore($user->id_pengguna, 'id_pengguna')],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'alamat_instansi' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->fill([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'username' => $data['username'],
            'no_telepon' => $data['no_telepon'] ?? null,
        ]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        if ($user->pelanggan) {
            $user->pelanggan->update([
                'nama' => $user->nama,
                'email' => $user->email,
                'no_telepon' => $user->no_telepon ?: $user->pelanggan->no_telepon,
                'alamat_instansi' => $data['alamat_instansi'] ?? $user->pelanggan->alamat_instansi,
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
