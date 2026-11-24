<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Catering Calakan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="app-body role-{{ auth()->user()->peran }}">
<div class="backdrop" onclick="document.body.classList.remove('nav-open')"></div>
<div class="shell">
    <aside class="sidebar" id="sidebar">
        <div class="side-brand">
            <x-logo />
            <div class="side-name">
                <strong>CALAKAN</strong>
                <span>BBGTK JABAR</span>
            </div>
            <button class="side-close" type="button" aria-label="Tutup sidebar" onclick="toggleSidebar()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 6l-6 6 6 6"/></svg>
            </button>
        </div>
        <p class="side-label">Menu</p>
        <nav class="nav">
            @php
                $on = fn (...$names) => request()->routeIs(...$names) ? 'is-active' : '';
                $peran = auth()->user()->peran;
            @endphp
            @if ($peran === 'pelanggan')
                <a class="{{ $on('pelanggan.dashboard') }}" href="{{ route('pelanggan.dashboard') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 11.5 12 4l8 7.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-8.5z"/></svg><span>Beranda</span></a>
                <a class="{{ $on('pelanggan.menu.*') }}" href="{{ route('pelanggan.menu.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 7h14M5 12h14M5 17h9"/></svg><span>Menu</span></a>
                <a class="{{ $on('pelanggan.pesanan.buat') }}" href="{{ route('pelanggan.pesanan.buat') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 7h15l-1.5 9h-12L5 4H3"/><circle cx="9" cy="20" r="1.2"/><circle cx="18" cy="20" r="1.2"/></svg><span>Keranjang</span>@if(($jumlahKeranjang ?? 0) > 0)<span class="badge-count">{{ $jumlahKeranjang }}</span>@endif</a>
                <a class="{{ $on('pelanggan.pesanan.index', 'pelanggan.pesanan.show') }}" href="{{ route('pelanggan.pesanan.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg><span>Lacak Pesanan</span></a>
                <a class="{{ $on('pelanggan.riwayat') }}" href="{{ route('pelanggan.riwayat') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16v12H4z"/><path d="M8 10h8M8 14h5"/></svg><span>Riwayat</span></a>
            @elseif ($peran === 'admin')
                <a class="{{ $on('admin.dashboard') }}" href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 11.5 12 4l8 7.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-8.5z"/></svg><span>Dasbor</span></a>
                <a class="{{ $on('admin.pelanggan.*') }}" href="{{ route('admin.pelanggan.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><path d="M3.5 19c.8-3 2.8-4.5 5.5-4.5S14.2 16 15 19"/><circle cx="17" cy="9" r="2.2"/><path d="M16.5 14.5c2 .4 3.4 1.6 4 3.5"/></svg><span>Pelanggan</span></a>
                <a class="{{ $on('admin.kategori.*') }}" href="{{ route('admin.kategori.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h7l2 3h7v8H4z"/></svg><span>Kategori</span></a>
                <a class="{{ $on('admin.menu.*') }}" href="{{ route('admin.menu.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 7h14M5 12h14M5 17h9"/></svg><span>Menu</span></a>
                <a class="{{ $on('admin.pesanan.*') }}" href="{{ route('admin.pesanan.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 4h10v16H7z"/><path d="M10 8h4M10 12h4"/></svg><span>Pesanan</span></a>
                <a class="{{ $on('admin.pembayaran.*') }}" href="{{ route('admin.pembayaran.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg><span>Pembayaran</span></a>
                <a class="{{ $on('admin.laporan.*') }}" href="{{ route('admin.laporan.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 19V9M12 19V5M19 19v-7"/></svg><span>Laporan</span></a>
            @elseif ($peran === 'koki')
                <a class="{{ $on('koki.pesanan.*') }}" href="{{ route('koki.pesanan.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 8c0-4 8-4 8 0v11H8z"/><path d="M6 19h12"/></svg><span>Pesanan Produksi</span></a>
            @else
                <a class="{{ $on('pengelola.dashboard') }}" href="{{ route('pengelola.dashboard') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 11.5 12 4l8 7.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-8.5z"/></svg><span>Ikhtisar</span></a>
                <a class="{{ $on('pengelola.laporan.*') }}" href="{{ route('pengelola.laporan.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 19V9M12 19V5M19 19v-7"/></svg><span>Laporan</span></a>
                <a class="{{ $on('pengelola.transaksi.*') }}" href="{{ route('pengelola.transaksi.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg><span>Data Transaksi</span></a>
            @endif
            <a class="{{ $on('profil.*') }}" href="{{ route('profil.edit') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5 19c1.2-3 3.4-4.5 7-4.5s5.8 1.5 7 4.5"/></svg><span>Profil</span></a>
        </nav>
        <div class="side-foot">
            <div class="side-user">
                <span class="avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</span>
                <div>
                    <strong>{{ auth()->user()->nama }}</strong>
                    <small>{{ auth()->user()->labelPeran() }}</small>
                </div>
            </div>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button class="logout" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 7V5a1 1 0 0 1 1-1h8v16h-8a1 1 0 0 1-1-1v-2"/><path d="M4 12h11M12 8l4 4-4 4"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    <div class="main">
        <header class="topbar">
            <button class="burger" type="button" aria-label="Buka atau tutup sidebar" onclick="toggleSidebar()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
            <div>
                <p class="eyebrow">@yield('kicker', auth()->user()->labelPeran())</p>
                <h1>@yield('heading')</h1>
            </div>
            <div class="top-actions">
                @if (auth()->user()->peran === 'pelanggan')
                    <a class="icon-btn {{ request()->routeIs('pelanggan.pesanan.buat') ? 'is-on' : '' }}" href="{{ route('pelanggan.pesanan.buat') }}" aria-label="Keranjang">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 7h15l-1.5 9h-12L5 4H3"/><circle cx="9" cy="20" r="1.2"/><circle cx="18" cy="20" r="1.2"/></svg>
                        @if (($jumlahKeranjang ?? 0) > 0)<span class="dot">{{ $jumlahKeranjang }}</span>@endif
                    </a>
                    <a class="icon-btn {{ request()->routeIs('pelanggan.pesanan.index', 'pelanggan.pesanan.show') ? 'is-on' : '' }}" href="{{ route('pelanggan.pesanan.index') }}" aria-label="Notifikasi pesanan">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 16V10a6 6 0 1 1 12 0v6l1.5 2H4.5L6 16z"/><path d="M10 19a2 2 0 0 0 4 0"/></svg>
                        @if (($jumlahNotifikasi ?? 0) > 0)<span class="dot">{{ $jumlahNotifikasi }}</span>@endif
                    </a>
                @endif
                <div class="top-user">
                    <span class="avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</span>
                    <div>
                        <strong>{{ auth()->user()->nama }}</strong>
                        <span>{{ auth()->user()->labelPeran() }}</span>
                    </div>
                </div>
            </div>
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>
@if (auth()->user()->peran === 'pelanggan')
    <nav class="tabbar" aria-label="Navigasi pelanggan">
        <a class="{{ request()->routeIs('pelanggan.dashboard') ? 'is-active' : '' }}" href="{{ route('pelanggan.dashboard') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 11.5 12 4l8 7.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-8.5z"/></svg>
            <span>Beranda</span>
        </a>
        <a class="{{ request()->routeIs('pelanggan.menu.*') ? 'is-active' : '' }}" href="{{ route('pelanggan.menu.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 7h14M5 12h14M5 17h9"/></svg>
            <span>Menu</span>
        </a>
        <a class="{{ request()->routeIs('pelanggan.pesanan.buat') ? 'is-active' : '' }}" href="{{ route('pelanggan.pesanan.buat') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 7h15l-1.5 9h-12L5 4H3"/><circle cx="9" cy="20" r="1.2"/><circle cx="18" cy="20" r="1.2"/></svg>
            <span>Keranjang</span>
        </a>
        <a class="{{ request()->routeIs('pelanggan.pesanan.index', 'pelanggan.pesanan.show') ? 'is-active' : '' }}" href="{{ route('pelanggan.pesanan.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg>
            <span>Pesanan</span>
        </a>
        <a class="{{ request()->routeIs('profil.*') ? 'is-active' : '' }}" href="{{ route('profil.edit') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5 19c1.2-3 3.4-4.5 7-4.5s5.8 1.5 7 4.5"/></svg>
            <span>Profil</span>
        </a>
    </nav>
@endif
<x-toasts />
<div class="modal" id="modal-hapus" hidden>
    <div class="modal-card">
        <h3>Konfirmasi</h3>
        <p id="hapus-pesan">Yakin ingin menghapus data ini?</p>
        <form id="form-hapus" method="post">
            @csrf
            @method('DELETE')
            <div class="actions">
                <button class="btn btn-bad" type="submit">Ya, hapus</button>
                <button class="btn btn-line" type="button" onclick="tutupModal('modal-hapus')">Batal</button>
            </div>
        </form>
    </div>
</div>
<script>
function bukaModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.hidden = false;
    document.body.classList.add('modal-open');
}
function tutupModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.hidden = true;
    if (!document.querySelector('.modal:not([hidden])')) document.body.classList.remove('modal-open');
}
function konfirmasiHapus(url, pesan) {
    document.getElementById('form-hapus').action = url;
    document.getElementById('hapus-pesan').textContent = pesan;
    bukaModal('modal-hapus');
}
document.querySelectorAll('.modal').forEach((modal) => {
    modal.addEventListener('click', (event) => {
        if (event.target === modal) tutupModal(modal.id);
    });
});
</script>
<script>
function lebar() {
    return window.matchMedia('(min-width: 981px)').matches;
}
if (lebar() && localStorage.getItem('calakan-sidebar') === 'tutup') {
    document.body.classList.add('nav-collapsed');
}
function toggleSidebar() {
    if (lebar()) {
        document.body.classList.toggle('nav-collapsed');
        document.body.classList.remove('nav-open');
        localStorage.setItem('calakan-sidebar', document.body.classList.contains('nav-collapsed') ? 'tutup' : 'buka');
        return;
    }
    document.body.classList.toggle('nav-open');
}
document.querySelectorAll('.sidebar a').forEach((link) => {
    link.addEventListener('click', () => {
        if (!lebar()) document.body.classList.remove('nav-open');
    });
});
</script>
@stack('scripts')
</body>
</html>
