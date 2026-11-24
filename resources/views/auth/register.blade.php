<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar — Catering Calakan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page register-page">
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
            <h2>Buat Akun</h2>
            <p class="login-lead">Daftar sebagai pelanggan Catering Calakan</p>
            <x-alerts />
            <form class="stack" method="post" action="{{ url('/daftar') }}">
                @csrf
                <div class="grid-2">
                    <label class="field">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5 19c1.4-3 3.8-4.5 7-4.5S17.6 16 19 19"/></svg>
                        <input name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap" autocomplete="name" required>
                    </label>
                    <label class="field">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="6" width="16" height="12" rx="2"/><path d="M4 8l8 6 8-6"/></svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" autocomplete="email" required>
                    </label>
                </div>
                @error('nama') <small class="err">{{ $message }}</small> @enderror
                @error('email') <small class="err">{{ $message }}</small> @enderror
                <div class="grid-2">
                    <label class="field">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8l1 3v13H7V7l1-3z"/><path d="M10 11h4"/></svg>
                        <input name="no_telepon" value="{{ old('no_telepon') }}" placeholder="No. Telepon" autocomplete="tel" required>
                    </label>
                    <label class="field">
                        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="10" r="2.4"/><path d="M8.5 16.5c.8-1.5 2-2.2 3.5-2.2s2.7.7 3.5 2.2"/></svg>
                        <input name="username" value="{{ old('username') }}" placeholder="Username" autocomplete="username" required>
                    </label>
                </div>
                @error('no_telepon') <small class="err">{{ $message }}</small> @enderror
                @error('username') <small class="err">{{ $message }}</small> @enderror
                <label class="field">
                    <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V8a4 4 0 0 1 8 0v2"/></svg>
                    <input type="password" name="password" placeholder="Password" autocomplete="new-password" required>
                    <button class="eye" type="button" data-toggle-pass aria-label="Tampilkan password">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/><circle cx="12" cy="12" r="2.5"/></svg>
                    </button>
                </label>
                @error('password') <small class="err">{{ $message }}</small> @enderror
                <label class="check">
                    <input type="checkbox" name="syarat" value="1" @checked(old('syarat'))>
                    <span>Saya menyetujui <a class="link" href="{{ route('syarat') }}">Syarat &amp; Ketentuan</a> dan <a class="link" href="{{ route('privasi') }}">Kebijakan Privasi</a> Catering Calakan.</span>
                </label>
                @error('syarat') <small class="err">{{ $message }}</small> @enderror
                <button class="btn btn-block login-submit" type="submit">Daftar Sekarang →</button>
            </form>
            <p class="or">atau</p>
            <a class="btn btn-block btn-line login-outline" href="{{ route('login') }}">Sudah punya akun? Masuk</a>
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
