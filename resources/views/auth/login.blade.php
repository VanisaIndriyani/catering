<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — Catering Calakan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
<div class="login-stage">
    <section class="login-hero" style="--hero: url('{{ asset('img/hero-makanan.jpg') }}')">
        <div class="login-hero-top">
            <x-logo class="login-seal" />
            <div>
                <strong>Catering Calakan</strong>
                <small>BBGTK JABAR</small>
            </div>
        </div>
        <div class="login-hero-copy">
            <h1>Catering<br>Calakan</h1>
            <p class="login-kicker">BBGTK JABAR</p>
            <p>Layanan catering sehat dan berkualitas untuk berbagai kebutuhan Anda.</p>
        </div>
    </section>

    <section class="login-side">
        <svg class="login-leaf login-leaf-tr" viewBox="0 0 180 220" fill="none" aria-hidden="true">
            <path d="M120 18c46 42 38 112-8 150-28-48-18-108 8-150z" fill="#9aaf96"/>
            <path d="M96 36c34 36 22 92-14 128" stroke="#6f8b68" stroke-width="2"/>
            <path d="M138 62c26 22 22 64-4 92-18-32-10-66 4-92z" fill="#c5d4be"/>
        </svg>
        <svg class="login-leaf login-leaf-bl" viewBox="0 0 180 220" fill="none" aria-hidden="true">
            <path d="M120 18c46 42 38 112-8 150-28-48-18-108 8-150z" fill="#a9bc9f"/>
            <path d="M138 62c26 22 22 64-4 92-18-32-10-66 4-92z" fill="#d5e0cf"/>
        </svg>
        <div class="login-card">
            <x-logo class="login-mark" />
            <h2>Selamat Datang</h2>
            <p class="login-lead">Login ke akun Anda</p>
            <x-alerts />
            <form class="stack" method="post" action="{{ url('/login') }}">
                @csrf
                <label class="field">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5 19c1.4-3 3.8-4.5 7-4.5S17.6 16 19 19"/></svg>
                    <input name="login" value="{{ old('login') }}" placeholder="Email atau Username" autocomplete="username" required>
                </label>
                @error('login') <small class="err">{{ $message }}</small> @enderror
                <label class="field">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V8a4 4 0 0 1 8 0v2"/></svg>
                    <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
                    <button class="eye" type="button" data-toggle-pass aria-label="Tampilkan password">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/><circle cx="12" cy="12" r="2.5"/></svg>
                    </button>
                </label>
                @error('password') <small class="err">{{ $message }}</small> @enderror
                <div class="row-between">
                    <label class="check"><input type="checkbox" name="ingat" value="1"> Ingat Saya</label>
                    <a class="link" href="{{ route('password.request') }}">Lupa Password?</a>
                </div>
                <button class="btn btn-block login-submit" type="submit">Login →</button>
            </form>
            <p class="or">atau</p>
            <a class="btn btn-block btn-line login-outline" href="{{ route('register') }}">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3"/><path d="M5 19c1.2-2.6 3.4-4 7-4s5.8 1.4 7 4"/></svg>
                Belum punya akun? Daftar Sekarang
            </a>
            <details class="login-demo">
                <summary>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                    Lihat demo untuk uji coba
                </summary>
                <table>
                    <tr><td>Pelanggan</td><td>pelanggan</td><td>pelanggan123</td></tr>
                    <tr><td>Admin</td><td>admin</td><td>admin123</td></tr>
                    <tr><td>Koki</td><td>koki</td><td>koki123</td></tr>
                    <tr><td>Pengelola</td><td>pengelola</td><td>pengelola123</td></tr>
                </table>
            </details>
        </div>
    </section>
</div>
<x-toasts />
<script>
document.querySelectorAll('[data-toggle-pass]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const input = btn.parentElement.querySelector('input');
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
    });
});
</script>
</body>
</html>
