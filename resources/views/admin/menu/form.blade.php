@extends('layouts.app')
@section('title', $menu->exists ? 'Ubah Menu' : 'Tambah Menu')
@section('heading', $menu->exists ? 'Ubah Menu' : 'Tambah Menu')
@section('content')
    <section class="card" style="max-width:760px">
        <form class="stack" method="post" action="{{ $menu->exists ? route('admin.menu.update', $menu) : route('admin.menu.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($menu->exists) @method('PUT') @endif
            <label>Nama menu<input class="plain-input" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" required></label>
            <label>Kategori
                <select class="plain-input" name="id_kategori" required>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id_kategori }}" @selected(old('id_kategori', $menu->id_kategori) == $item->id_kategori)>{{ $item->nama_kategori }}</option>
                    @endforeach
                </select>
            </label>
            <label>Deskripsi<textarea class="plain-input" name="deskripsi">{{ old('deskripsi', $menu->deskripsi) }}</textarea></label>
            <div class="grid-2">
                <label>Harga<input class="plain-input" type="number" min="0" step="500" name="harga" value="{{ old('harga', $menu->harga) }}" required></label>
                <label>Status
                    <select class="plain-input" name="status">
                        <option value="aktif" @selected(old('status', $menu->status) === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status', $menu->status) === 'nonaktif')>Nonaktif</option>
                    </select>
                </label>
            </div>
            <label class="check"><input type="checkbox" name="ketersediaan" value="1" @checked(old('ketersediaan', $menu->ketersediaan))> Tersedia untuk dipesan</label>
            <label>Foto<input class="plain-input" type="file" name="foto" accept="image/*"></label>
            @if ($menu->foto_url)
                <img src="{{ $menu->foto_url }}" alt="" style="width:160px;height:110px;object-fit:cover;border-radius:12px">
            @endif
            @if ($errors->any())
                <div class="alert bad">{{ $errors->first() }}</div>
            @endif
            <div class="actions">
                <button class="btn" type="submit">Simpan</button>
                <a class="btn btn-line" href="{{ route('admin.menu.index') }}">Batal</a>
            </div>
        </form>
    </section>
@endsection
