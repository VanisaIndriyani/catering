@extends('layouts.app')
@section('title', 'Menu')
@section('heading', 'Menu')
@section('content')
    <form class="catalog-bar" method="get">
        @if (request()->filled('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif
        <label class="catalog-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l4 4"/></svg>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari menu">
        </label>
        <label class="check"><input type="checkbox" name="tersedia" value="1" @checked(request()->boolean('tersedia')) onchange="this.form.submit()"> Hanya yang tersedia</label>
        <button class="btn btn-sm" type="submit">Cari</button>
    </form>

    <div class="cat-row">
        <a class="cat-pill {{ request('kategori') ? '' : 'is-on' }}" href="{{ route('pelanggan.menu.index', request()->except('kategori', 'page')) }}">Semua</a>
        @foreach ($kategori as $item)
            <a class="cat-pill {{ request('kategori') == $item->id_kategori ? 'is-on' : '' }}" href="{{ route('pelanggan.menu.index', array_merge(request()->except('page'), ['kategori' => $item->id_kategori])) }}">{{ $item->nama_kategori }}</a>
        @endforeach
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
                    @if ($item->deskripsi)
                        <p class="dish-desc">{{ $item->deskripsi }}</p>
                    @endif
                    @if ($item->tersedia())
                        <span class="badge tone-ok">Tersedia</span>
                    @else
                        <span class="badge tone-bad">Habis</span>
                    @endif
                    <div class="dish-foot">
                        <span class="price">{{ rupiah($item->harga) }}</span>
                        <form method="post" action="{{ route('pelanggan.menu.tambah', $item) }}" class="dish-order">
                            @csrf
                            <input class="qty" type="number" name="jumlah" min="1" max="500" value="1" aria-label="Jumlah porsi" @disabled(! $item->tersedia())>
                            <x-aksi ikon="tambah" label="Pesan" type="submit" :disabled="! $item->tersedia()" />
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="home-block catalog-empty">Menu tidak ditemukan. Coba kata kunci lain.</div>
        @endforelse
    </div>
    {{ $menu->links() }}
@endsection
