@extends('layouts.app')
@section('title', 'Pengelola')
@section('heading', 'Ikhtisar')
@section('content')
    <section class="home-hero">
        <p class="home-date">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h2>Data operasional Calakan</h2>
        <p>Ringkasan {{ now()->translatedFormat('F Y') }}. Pantau omzet, pesanan, dan pembayaran tanpa mengubah data dapur.</p>
        <a class="home-cta" href="{{ route('pengelola.laporan.index') }}">
            Lihat laporan
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
    </section>

    <section class="home-stats">
        <article class="home-stat">
            <span class="mark gold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 19V9M12 19V5M19 19v-7"/></svg>
            </span>
            <div>
                <span>Omzet terverifikasi</span>
                <strong>{{ rupiah($omzet) }}</strong>
                <small>bulan ini</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 4h10v16H7z"/><path d="M10 8h4M10 12h4"/></svg>
            </span>
            <div>
                <span>Pesanan</span>
                <strong>{{ $pesanan }}</strong>
                <small>masuk bulan ini</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 13l4 4L19 7"/></svg>
            </span>
            <div>
                <span>Selesai</span>
                <strong>{{ $selesai }}</strong>
                <small>pesanan bulan ini</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg>
            </span>
            <div>
                <span>Menunggu bayar</span>
                <strong>{{ $menunggu }}</strong>
                <small>belum terverifikasi</small>
            </div>
        </article>
    </section>

    <section class="home-quick">
        <a href="{{ route('pengelola.laporan.index') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 19V9M12 19V5M19 19v-7"/></svg></span>
            <span><strong>Laporan pesanan</strong><small>Rekap per periode</small></span>
        </a>
        <a href="{{ route('pengelola.laporan.index', ['jenis' => 'transaksi']) }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg></span>
            <span><strong>Laporan transaksi</strong><small>Pembayaran yang tercatat</small></span>
        </a>
        <a href="{{ route('pengelola.transaksi.index') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16v12H4z"/><path d="M8 10h8M8 14h5"/></svg></span>
            <span><strong>Data transaksi</strong><small>Cari berdasarkan nomor pesanan</small></span>
        </a>
        <a href="{{ route('profil.edit') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5 19c1.2-3 3.4-4.5 7-4.5s5.8 1.5 7 4.5"/></svg></span>
            <span><strong>Profil</strong><small>Perbarui akun pengelola</small></span>
        </a>
    </section>
@endsection
