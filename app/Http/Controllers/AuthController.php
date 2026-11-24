<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function beranda()
    {
        if ($user = auth()->user()) {
            return redirect($user->beranda());
        }

        return view('auth.login');
    }

    public function formMasuk()
    {
        return view('auth.login');
    }

    public function masuk(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($data['login']);
        $user = User::query()
            ->where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $data['password']])) {
            return back()
                ->withErrors(['login' => 'Email, username, atau password salah.'])
                ->onlyInput('login');
        }

        Auth::login($user, $request->boolean('ingat'));
        $request->session()->regenerate();

        return redirect($user->beranda())->with('success', 'Selamat datang, '.$user->nama.'.');
    }

    public function formDaftar()
    {
        return view('auth.register');
    }

    public function daftar(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:pengguna,email'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:pengguna,username'],
            'password' => ['required', 'string', 'min:6'],
            'syarat' => ['accepted'],
        ]);

        $user = User::create([
            'nama' => $data['nama'],
            'email' => Str::lower($data['email']),
            'no_telepon' => $data['no_telepon'],
            'username' => Str::lower($data['username']),
            'password' => $data['password'],
            'peran' => 'pelanggan',
        ]);

        Pelanggan::create([
            'id_pengguna' => $user->id_pengguna,
            'nama' => $user->nama,
            'email' => $user->email,
            'no_telepon' => $user->no_telepon,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pelanggan.dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang di Catering Calakan.');
    }

    public function keluar(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function lupa()
    {
        return view('auth.lupa');
    }

    public function syarat()
    {
        return view('auth.syarat');
    }

    public function privasi()
    {
        return view('auth.privasi');
    }

    public function google()
    {
        return redirect()
            ->route('login')
            ->with('info', 'Masuk dengan Google belum diaktifkan. Silakan gunakan email atau username dan password.');
    }
}
