@extends('layouts.app')
@section('title', 'Keranjang')
@section('heading', 'Keranjang')
@section('content')
    @if ($baris === [])
        <section class="home-block empty-cart">
            <span class="q-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 7h15l-1.5 9h-12L5 4H3"/><circle cx="9" cy="20" r="1.2"/><circle cx="18" cy="20" r="1.2"/></svg>
            </span>
            <h3>Keranjang masih kosong</h3>
            <p>Pilih menu catering, lalu atur porsi dan tanggal kebutuhan di sini.</p>
            <a class="btn" href="{{ route('pelanggan.menu.index') }}">Pilih Menu</a>
        </section>
    @else
        <div class="cart-layout">
            <section class="home-block">
                <div class="dash-head">
                    <h3>Rincian pesanan</h3>
                    <x-aksi ikon="tambah" label="Tambah menu" href="{{ route('pelanggan.menu.index') }}" />
                </div>
                @foreach ($baris as $item)
                    <article class="cart-item">
                        <div class="cart-photo" @if($item['menu']->foto_url) style="background-image:url('{{ $item['menu']->foto_url }}')" @endif>
                            @unless($item['menu']->foto_url)<span>{{ strtoupper(substr($item['menu']->nama_menu, 0, 1)) }}</span>@endunless
                        </div>
                        <div class="cart-info">
                            @if ($item['menu']->kategori)
                                <span class="dish-cat">{{ $item['menu']->kategori->nama_kategori }}</span>
                            @endif
                            <strong>{{ $item['menu']->nama_menu }}</strong>
                            <span class="muted">{{ rupiah($item['menu']->harga) }} / porsi</span>
                            @unless($item['menu']->tersedia())
                                <span class="badge tone-bad">Tidak tersedia</span>
                            @endunless
                        </div>
                        <form class="dish-order" method="post" action="{{ route('pelanggan.keranjang.ubah', $item['menu']) }}">
                            @csrf
                            @method('PATCH')
                            <input class="qty" type="number" name="jumlah" min="1" max="500" value="{{ $item['jumlah'] }}" aria-label="Jumlah porsi {{ $item['menu']->nama_menu }}">
                            <x-aksi ikon="ubah" label="Ubah jumlah" type="submit" />
                        </form>
                        <strong class="cart-sub">{{ rupiah($item['sub']) }}</strong>
                        <form method="post" action="{{ route('pelanggan.keranjang.hapus', $item['menu']) }}">
                            @csrf
                            @method('DELETE')
                            <x-aksi ikon="hapus" label="Hapus dari keranjang" type="submit" />
                        </form>
                    </article>
                @endforeach
            </section>
            <section class="home-block cart-side">
                <h3>Simpan pesanan</h3>
                <form class="stack" method="post" action="{{ route('pelanggan.pesanan.simpan') }}" enctype="multipart/form-data">
                    @csrf
                    <label class="field-block">Tanggal kebutuhan
                        <input class="plain-input" type="date" name="tanggal_kebutuhan" value="{{ old('tanggal_kebutuhan', now()->addDay()->toDateString()) }}" required>
                    </label>
                    @error('tanggal_kebutuhan') <small class="err">{{ $message }}</small> @enderror
                    <label class="field-block">Catatan
                        <textarea class="plain-input" name="catatan" placeholder="Contoh: antar ke aula utama">{{ old('catatan') }}</textarea>
                    </label>
                    <div class="rekening">
                        <p class="rekening-brand">Catering KPN Tut Wuri</p>
                        <p class="rekening-bank">BRI</p>
                        <p class="rekening-nomor">1433 0100 0101 569</p>
                        <p class="rekening-nama">a/n Koperasi Pegawai Negeri Tut Wuri</p>
                    </div>
                    <label class="field-block">Bukti transfer
                        <input class="plain-input bukti-file" type="file" name="bukti" accept="image/jpeg,image/png,image/webp" required>
                    </label>
                    <small class="muted">Foto JPG, PNG, atau WEBP, maksimal 2 MB.</small>
                    @error('bukti') <small class="err">{{ $message }}</small> @enderror
                    <div class="cart-total">
                        <span>Total</span>
                        <strong>{{ rupiah($total) }}</strong>
                    </div>
                    <button class="btn btn-block" type="submit">Simpan Pesanan</button>
                </form>
            </section>
        </div>
    @endif
@endsection
