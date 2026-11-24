@extends('layouts.app')
@section('title', 'Beranda')
@section('heading', 'Beranda')
@section('content')
    <section class="home-hero">
        <p class="home-date">{{ now()->translatedFormat('l, d F Y') }}</p>
        <h2>Halo, {{ $pelanggan->nama }}</h2>
        <p>Siapkan konsumsi rapat, diklat, atau acara di BBGTK Jabar dari satu halaman.</p>
        <a class="home-cta" href="{{ route('pelanggan.menu.index') }}">
            Pesan sekarang
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
    </section>

    <section class="home-stats">
        <article class="home-stat">
            <span class="mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg>
            </span>
            <div>
                <span>Pesanan aktif</span>
                <strong>{{ $aktif }}</strong>
                <small>masih berjalan</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark gold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v18M7 7.5c1.5-2 8.5-2 10 0s-2 5-5 5-6.5 3-5 5 8.5 2 10 0"/></svg>
            </span>
            <div>
                <span>Pesanan selesai</span>
                <strong>{{ rupiah($total) }}</strong>
                <small>total nilai</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 7h15l-1.5 9h-12L5 4H3"/><circle cx="9" cy="20" r="1.2"/><circle cx="18" cy="20" r="1.2"/></svg>
            </span>
            <div>
                <span>Keranjang</span>
                <strong>{{ (int) collect(session('cart', []))->sum('jumlah') }}</strong>
                <small>porsi dipilih</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20V9l8-5 8 5v11"/><path d="M9 20v-6h6v6"/></svg>
            </span>
            <div>
                <span>Instansi</span>
                <strong class="is-text">{{ $pelanggan->alamat_instansi ?: 'Belum diisi' }}</strong>
                <small><a class="link" href="{{ route('profil.edit') }}">Kelola profil</a></small>
            </div>
        </article>
    </section>

    <section class="home-quick">
        <a href="{{ route('pelanggan.menu.index') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 7h14M5 12h14M5 17h9"/></svg></span>
            <span><strong>Lihat menu</strong><small>Pilih nasi box, snack, atau paket</small></span>
        </a>
        <a href="{{ route('pelanggan.pesanan.buat') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 7h15l-1.5 9h-12L5 4H3"/><circle cx="9" cy="20" r="1.2"/><circle cx="18" cy="20" r="1.2"/></svg></span>
            <span><strong>Keranjang</strong><small>Cek pesanan sebelum disimpan</small></span>
        </a>
        <a href="{{ route('pelanggan.pesanan.index') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg></span>
            <span><strong>Lacak pesanan</strong><small>{{ $aktif }} pesanan sedang berjalan</small></span>
        </a>
        <a href="{{ route('pelanggan.riwayat') }}">
            <span class="q-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16v12H4z"/><path d="M8 10h8M8 14h5"/></svg></span>
            <span><strong>Riwayat</strong><small>Pesanan yang sudah selesai</small></span>
        </a>
    </section>

    <section class="home-block">
        <div class="dash-head">
            <h3>Menu pilihan hari ini</h3>
            <a class="link" href="{{ route('pelanggan.menu.index') }}">Lihat semua</a>
        </div>
        <div class="home-menu">
            @forelse ($menu as $item)
                <article class="dish">
                    <div class="dish-photo" @if($item->foto_url) style="background-image:url('{{ $item->foto_url }}')" @endif>
                        @unless($item->foto_url)<span>{{ strtoupper(substr($item->nama_menu, 0, 1)) }}</span>@endunless
                    </div>
                    <div class="dish-body">
                        <span class="dish-cat">{{ $item->kategori->nama_kategori }}</span>
                        <h3>{{ $item->nama_menu }}</h3>
                        <div class="dish-foot">
                            <span class="price">{{ rupiah($item->harga) }}</span>
                            <form method="post" action="{{ route('pelanggan.menu.tambah', $item) }}">
                                @csrf
                                <input type="hidden" name="jumlah" value="1">
                                <x-aksi ikon="tambah" label="Pesan" type="submit" />
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty">Menu hari ini belum tersedia.</div>
            @endforelse
        </div>
    </section>

    <section class="home-block">
        <div class="dash-head"><h3>Kategori menu</h3></div>
        <div class="cat-row">
            <a class="cat-pill is-on" href="{{ route('pelanggan.menu.index') }}">Semua</a>
            @foreach ($kategori as $item)
                <a class="cat-pill" href="{{ route('pelanggan.menu.index', ['kategori' => $item->id_kategori]) }}">{{ $item->nama_kategori }}</a>
            @endforeach
        </div>
    </section>

    <section class="home-block">
        <div class="dash-head">
            <h3>Pesanan terbaru</h3>
            <a class="link" href="{{ route('pelanggan.pesanan.index') }}">Lacak semua</a>
        </div>
        @if ($pesanan->isEmpty())
            <div class="empty">Belum ada pesanan. Mulai dari menu hari ini.</div>
        @else
            <div class="order-list">
                @foreach ($pesanan as $item)
                    <a class="order-row" href="{{ route('pelanggan.pesanan.show', $item) }}">
                        <div><strong>{{ $item->no_pesanan }}</strong><span class="muted">Butuh {{ $item->tanggal_kebutuhan->translatedFormat('d M Y') }}</span></div>
                        <span>{{ rupiah($item->total_harga) }}</span>
                        <x-badge group="pesanan" :value="$item->status_pesanan" />
                        @if($item->pembayaran)<x-badge group="bayar" :value="$item->pembayaran->status_verifikasi" />@endif
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
