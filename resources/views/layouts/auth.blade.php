<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') — Catering Calakan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth-shell">
    <section class="hero">
        <div class="hero-top">
            <div>
                <h1>Catering Calakan</h1>
                <p class="sub">BBGTK JABAR</p>
            </div>
        </div>
        <div class="hero-bottom">
            <h2>Dari Dapur<br>untuk<br>Kebersamaan</h2>
        </div>
    </section>
    <section class="auth-panel">
        <svg class="leaf leaf-tr" viewBox="0 0 200 260" fill="none">
            <path d="M150 20c40 50 30 120-10 160-30-50-20-110 10-160z" fill="#8ea48a"/>
            <path d="M120 40c30 40 20 100-16 140" stroke="#6d8b6a" stroke-width="2"/>
            <path d="M168 70c28 24 24 70-6 100-20-36-10-72 6-100z" fill="#b7c7ae"/>
        </svg>
        <svg class="leaf leaf-bl" viewBox="0 0 200 260" fill="none">
            <path d="M150 20c40 50 30 120-10 160-30-50-20-110 10-160z" fill="#8ea48a"/>
            <path d="M168 70c28 24 24 70-6 100-20-36-10-72 6-100z" fill="#c5d2bc"/>
        </svg>
        <div class="auth-card">
            <div class="brand">
                <x-logo />
                <strong>Catering Calakan</strong>
                <small>BBGTK JABAR</small>
            </div>
            @yield('form')
        </div>
    </section>
</div>
<x-toasts />
@stack('scripts')
</body>
</html>
