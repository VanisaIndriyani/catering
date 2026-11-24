<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Catering Calakan')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<main class="auth-panel" style="min-height:100vh">
    <svg class="leaf leaf-tr" viewBox="0 0 200 260" fill="none"><path d="M150 20c40 50 30 120-10 160-30-50-20-110 10-160z" fill="#8ea48a"/></svg>
    <div class="auth-card" style="width:min(720px,100%)">
        <div class="brand">
            <x-logo />
            <strong>Catering Calakan</strong>
            <small>BBGTK JABAR</small>
        </div>
        <x-toasts />
        @yield('content')
    </div>
</main>
</body>
</html>
