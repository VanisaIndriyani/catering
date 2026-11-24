<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password — Catering Calakan</title>
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
            <h2>Lupa Password</h2>
            <p class="login-lead">Password diatur ulang oleh admin</p>
            <ol class="login-steps">
                <li>
                    <b>1</b>
                    <div>
                        <strong>Hubungi admin</strong>
                        <small>BBGTK Jabar / Catering KPN Tut Wuri</small>
                    </div>
                </li>
                <li>
                    <b>2</b>
                    <div>
                        <strong>Sebutkan akun</strong>
                        <small>Email atau username yang dipakai masuk</small>
                    </div>
                </li>
                <li>
                    <b>3</b>
                    <div>
                        <strong>Admin ganti password</strong>
                        <small>Dari halaman kelola pelanggan, lalu masuk lagi</small>
                    </div>
                </li>
            </ol>
            <p class="login-note">Akun demo untuk uji coba ada di halaman masuk, bagian “Lihat demo untuk uji coba”.</p>
            <a class="btn btn-block login-submit" href="{{ route('login') }}">Kembali ke Login</a>
        </div>
    </section>
</div>
</body>
</html>
