@extends('layouts.app')
@section('title', 'Dasbor Admin')
@section('heading', 'Dasbor')
@section('content')
    <section class="home-hero">
        <p class="home-date">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h2>Operasional hari ini</h2>
        <p>Konfirmasi pesanan, cek pembayaran, dan pantau dapur dari satu tempat.</p>
        <a class="home-cta" href="{{ route('admin.pesanan.index', ['status' => 'menunggu']) }}">
            {{ $menunggu }} menunggu konfirmasi
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
    </section>

    <section class="home-stats">
        <article class="home-stat">
            <span class="mark gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 4h10v16H7z"/><path d="M10 8h4M10 12h4"/></svg></span>
            <div>
                <span>Menunggu konfirmasi</span>
                <strong>{{ $menunggu }}</strong>
                <small>pesanan baru</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark soft"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg></span>
            <div>
                <span>Pembayaran</span>
                <strong>{{ $bayar }}</strong>
                <small>menunggu verifikasi</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><path d="M4 19c1-3 3-4.5 5-4.5"/></svg></span>
            <div>
                <span>Pelanggan</span>
                <strong>{{ $pelanggan }}</strong>
                <small>{{ $menu }} menu aktif</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v18M7 7.5c1.5-2 8.5-2 10 0s-2 5-5 5-6.5 3-5 5 8.5 2 10 0"/></svg></span>
            <div>
                <span>Omzet bulan ini</span>
                <strong>{{ rupiah($omzet) }}</strong>
                <small>terverifikasi</small>
            </div>
        </article>
    </section>

    <section class="chart-grid">
        <article class="home-block">
            <div class="dash-head">
                <h3>Pesanan 7 hari</h3>
                <span class="muted">{{ $harian->sum('jumlah') }} pesanan</span>
            </div>
            <div class="bars" role="img" aria-label="Diagram batang pesanan tujuh hari terakhir">
                @foreach ($harian as $hari)
                    <div class="bar">
                        <b>{{ $hari['jumlah'] }}</b>
                        <i style="height: {{ $hari['jumlah'] > 0 ? max(12, (int) round($hari['jumlah'] / $puncak * 100)) : 0 }}%"></i>
                        <small title="{{ $hari['label'] }}">{{ $hari['tanggal'] }}</small>
                    </div>
                @endforeach
            </div>
        </article>
        <article class="home-block">
            <div class="dash-head"><h3>Status pesanan</h3></div>
            <div class="donut-wrap">
                <svg class="donut" viewBox="0 0 120 120" role="img" aria-label="Diagram status pesanan">
                    <circle cx="60" cy="60" r="42" fill="none" stroke="#f1ebe0" stroke-width="14"/>
                    @foreach ($status['iris'] as $iris)
                        <circle cx="60" cy="60" r="42" fill="none" stroke="{{ $iris['warna'] }}" stroke-width="14"
                            stroke-dasharray="{{ $iris['panjang'] }} {{ $iris['sisa'] }}"
                            stroke-dashoffset="{{ $iris['geser'] }}"
                            transform="rotate(-90 60 60)"/>
                    @endforeach
                    <text x="60" y="56" text-anchor="middle" class="donut-num">{{ $status['total'] }}</text>
                    <text x="60" y="72" text-anchor="middle" class="donut-cap">total</text>
                </svg>
                <ul class="legend">
                    @forelse ($status['iris'] as $iris)
                        <li>
                            <i style="background: {{ $iris['warna'] }}"></i>
                            <span>{{ $iris['label'] }}</span>
                            <b>{{ $iris['jumlah'] }}</b>
                        </li>
                    @empty
                        <li><span>Belum ada pesanan.</span></li>
                    @endforelse
                </ul>
            </div>
        </article>
    </section>

    <section class="home-block">
        <div class="dash-head">
            <h3>Verifikasi pembayaran</h3>
            <a class="link" href="{{ route('admin.pembayaran.index') }}">Buka pembayaran</a>
        </div>
        <div class="meter">
            @foreach ($pembayaran as $item)
                <div class="meter-row">
                    <span>{{ $item['label'] }}</span>
                    <span class="meter-track"><i style="width: {{ (int) round($item['jumlah'] / $totalBayar * 100) }}%; background: {{ $item['warna'] }}"></i></span>
                    <b>{{ $item['jumlah'] }}</b>
                </div>
            @endforeach
        </div>
    </section>

    <section class="home-quick">
        <a href="{{ route('admin.pesanan.index') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 4h10v16H7z"/><path d="M10 8h4M10 12h4"/></svg></span>
            <span><strong>Kelola pesanan</strong><small>Terima, tolak, atau ubah status</small></span>
        </a>
        <a href="{{ route('admin.pembayaran.index') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg></span>
            <span><strong>Verifikasi bayar</strong><small>{{ $bayar }} bukti perlu dicek</small></span>
        </a>
        <a href="{{ route('admin.menu.index') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 7h14M5 12h14M5 17h9"/></svg></span>
            <span><strong>Kelola menu</strong><small>Tambah atau ubah lewat modal</small></span>
        </a>
        <a href="{{ route('admin.laporan.index') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 19V9M12 19V5M19 19v-7"/></svg></span>
            <span><strong>Laporan</strong><small>Pesanan dan transaksi periode ini</small></span>
        </a>
    </section>

    <section class="home-block">
        <div class="dash-head"><h3>Pesanan terbaru</h3><a class="link" href="{{ route('admin.pesanan.index') }}">Lihat semua</a></div>
        <div class="order-list">
            @forelse ($terbaru as $item)
                <a class="order-row" href="{{ route('admin.pesanan.show', $item) }}">
                    <div><strong>{{ $item->no_pesanan }}</strong><span class="muted">{{ $item->pelanggan->nama }}</span></div>
                    <span>{{ rupiah($item->total_harga) }}</span>
                    <x-badge group="pesanan" :value="$item->status_pesanan" />
                    @if($item->pembayaran)<x-badge group="bayar" :value="$item->pembayaran->status_verifikasi" />@else <span></span> @endif
                </a>
            @empty
                <div class="empty">Belum ada pesanan.</div>
            @endforelse
        </div>
    </section>
@endsection
