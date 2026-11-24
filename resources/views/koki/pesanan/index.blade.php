@extends('layouts.app')
@section('title', 'Dapur')
@section('heading', 'Dapur')
@section('content')
    <section class="home-hero">
        <p class="home-date">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h2>Antrian dapur</h2>
        <p>Masak pesanan yang sudah diterima. Urutannya mengikuti tanggal kebutuhan.</p>
        <a class="home-cta" href="{{ route('koki.pesanan.index', ['produksi' => 'menunggu']) }}">
            {{ $ringkas['menunggu'] }} menunggu dapur
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
    </section>

    <section class="home-stats">
        <article class="home-stat">
            <span class="mark gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg></span>
            <div>
                <span>Menunggu dapur</span>
                <strong>{{ $ringkas['menunggu'] }}</strong>
                <small>belum dimasak</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 8c0-4 8-4 8 0v11H8z"/><path d="M6 19h12"/></svg></span>
            <div>
                <span>Sedang dimasak</span>
                <strong>{{ $ringkas['dimasak'] }}</strong>
                <small>di dapur sekarang</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 13l4 4L19 7"/></svg></span>
            <div>
                <span>Siap diserahkan</span>
                <strong>{{ $ringkas['siap'] }}</strong>
                <small>menunggu diambil</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark soft"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16v12H4z"/><path d="M8 7V5h8v2"/></svg></span>
            <div>
                <span>Produksi selesai</span>
                <strong>{{ $ringkas['selesai'] }}</strong>
                <small>sudah diserahkan</small>
            </div>
        </article>
    </section>

    <div class="cat-row">
        <a class="cat-pill {{ request('produksi') ? '' : 'is-on' }}" href="{{ route('koki.pesanan.index') }}">Semua</a>
        @foreach (['menunggu', 'dimasak', 'siap', 'selesai'] as $status)
            <a class="cat-pill {{ request('produksi') === $status ? 'is-on' : '' }}" href="{{ route('koki.pesanan.index', ['produksi' => $status]) }}">{{ \App\Support\Status::label('produksi', $status) }}</a>
        @endforeach
    </div>

    <section class="kitchen-list">
        @forelse ($pesanan as $item)
            @php
                $menu = $item->detail;
                $tampil = $menu->take(3);
                $sisa = $menu->count() - $tampil->count();
            @endphp
            <a class="kitchen-card" href="{{ route('koki.pesanan.show', $item) }}">
                <div class="kitchen-top">
                    <div>
                        <strong>{{ $item->no_pesanan }}</strong>
                        <span class="muted">{{ $item->pelanggan->nama }}</span>
                    </div>
                    <x-badge group="produksi" :value="$item->status_produksi" />
                </div>
                <div class="kitchen-meta">
                    <span>Butuh {{ $item->tanggal_kebutuhan->translatedFormat('d M Y') }}</span>
                    <span>{{ $menu->sum('jumlah_porsi') }} porsi</span>
                    @if ($item->pembayaran)
                        <x-badge group="bayar" :value="$item->pembayaran->status_verifikasi" />
                    @endif
                </div>
                <ul class="kitchen-menu">
                    @foreach ($tampil as $baris)
                        <li>
                            <span>{{ $baris->menu->nama_menu ?? 'Menu dihapus' }}</span>
                            <b>× {{ $baris->jumlah_porsi }}</b>
                        </li>
                    @endforeach
                    @if ($sisa > 0)
                        <li class="muted">+{{ $sisa }} menu lain</li>
                    @endif
                </ul>
            </a>
        @empty
            <div class="home-block empty">Belum ada pesanan untuk dapur.</div>
        @endforelse
    </section>
    @if ($pesanan->hasPages())
        <div>{{ $pesanan->links() }}</div>
    @endif
@endsection
