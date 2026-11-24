@extends('layouts.app')
@section('title', 'Menu')
@section('heading', 'Kelola Menu')
@section('content')
    @php
        $menuLama = old('menu_id');
    @endphp
    <div class="row-between">
        <form class="filters" method="get">
            <input class="plain-input search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari menu">
            <select class="plain-input" name="kategori">
                <option value="">Semua kategori</option>
                @foreach ($kategori as $item)
                    <option value="{{ $item->id_kategori }}" @selected(request('kategori') == $item->id_kategori)>{{ $item->nama_kategori }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm" type="submit">Filter</button>
        </form>
        <x-aksi ikon="tambah" label="Tambah menu" onclick="siapkanMenu()" />
    </div>
    <section class="card">
        <div class="table-wrap">
            <table class="data">
                <thead><tr><th>Menu</th><th>Kategori</th><th>Harga</th><th>Status</th><th class="kolom-aksi">Aksi</th></tr></thead>
                <tbody>
                @forelse ($menu as $item)
                    <tr>
                        <td>
                            <div class="menu-cell">
                                @if ($item->foto_url)
                                    <img class="menu-thumb" src="{{ $item->foto_url }}" alt="{{ $item->nama_menu }}">
                                @else
                                    <span class="menu-thumb">{{ strtoupper(substr($item->nama_menu, 0, 1)) }}</span>
                                @endif
                                <span>{{ $item->nama_menu }}</span>
                            </div>
                        </td>
                        <td>{{ $item->kategori->nama_kategori }}</td>
                        <td>{{ rupiah($item->harga) }}</td>
                        <td>
                            <span class="badge {{ $item->status === 'aktif' ? 'tone-ok' : 'tone-bad' }}">{{ ucfirst($item->status) }}</span>
                            @unless($item->ketersediaan)<span class="badge tone-wait">Habis</span>@endunless
                        </td>
                        <td class="actions">
                            @php($ubahMenu = 'siapkanMenu('.(string) \Illuminate\Support\Js::from(['id' => $item->id_menu, 'nama' => $item->nama_menu, 'kategori' => $item->id_kategori, 'deskripsi' => $item->deskripsi, 'harga' => (float) $item->harga, 'status' => $item->status, 'tersedia' => (bool) $item->ketersediaan]).')')
                            <x-aksi ikon="ubah" label="Ubah menu" :onclick="$ubahMenu" />
                            <x-aksi ikon="hapus" label="Hapus menu" onclick="konfirmasiHapus('{{ route('admin.menu.destroy', $item) }}', 'Hapus {{ $item->nama_menu }}? Jika sudah pernah dipesan, menu hanya dinonaktifkan.')" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Belum ada menu.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem">{{ $menu->links() }}</div>
    </section>
    <div class="modal" id="modal-menu" @if(! $errors->hasAny(['nama_menu','id_kategori','harga','deskripsi','foto','status'])) hidden @endif>
        <div class="modal-card">
            <h3 id="judul-menu">{{ $menuLama ? 'Ubah Menu' : 'Tambah Menu' }}</h3>
            <form id="form-menu" class="stack" method="post" enctype="multipart/form-data" action="{{ $menuLama ? route('admin.menu.update', $menuLama) : route('admin.menu.store') }}">
                @csrf
                <input type="hidden" name="_method" id="menu-method" value="{{ $menuLama ? 'PUT' : 'POST' }}">
                <input type="hidden" name="menu_id" id="menu-id" value="{{ $menuLama }}">
                <label>Nama menu<input class="plain-input" id="menu-nama" name="nama_menu" value="{{ old('nama_menu') }}" required></label>
                <label>Kategori
                    <select class="plain-input" id="menu-kategori" name="id_kategori" required>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id_kategori }}" @selected(old('id_kategori') == $item->id_kategori)>{{ $item->nama_kategori }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Deskripsi<textarea class="plain-input" id="menu-deskripsi" name="deskripsi">{{ old('deskripsi') }}</textarea></label>
                <div class="grid-2">
                    <label>Harga<input class="plain-input" id="menu-harga" type="number" min="0" step="500" name="harga" value="{{ old('harga') }}" required></label>
                    <label>Status
                        <select class="plain-input" id="menu-status" name="status">
                            <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                        </select>
                    </label>
                </div>
                <label class="check"><input type="hidden" name="ketersediaan" value="0"><input type="checkbox" id="menu-tersedia" name="ketersediaan" value="1" @checked(old('ketersediaan', '1') == '1')> Tersedia untuk dipesan</label>
                <label>Foto<input class="plain-input" type="file" name="foto" accept="image/*"></label>
                <div class="actions">
                    <button class="btn" type="submit">Simpan</button>
                    <button class="btn btn-line" type="button" onclick="tutupModal('modal-menu')">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
function siapkanMenu(menu) {
    const form = document.getElementById('form-menu');
    const ubah = Boolean(menu);
    form.action = ubah ? @js(url('/admin/menu')) + '/' + menu.id : @js(route('admin.menu.store'));
    document.getElementById('menu-method').value = ubah ? 'PUT' : 'POST';
    document.getElementById('menu-id').value = ubah ? menu.id : '';
    document.getElementById('menu-nama').value = ubah ? menu.nama : '';
    document.getElementById('menu-kategori').value = ubah ? menu.kategori : (document.getElementById('menu-kategori').options[0]?.value || '');
    document.getElementById('menu-deskripsi').value = ubah ? (menu.deskripsi || '') : '';
    document.getElementById('menu-harga').value = ubah ? menu.harga : '';
    document.getElementById('menu-status').value = ubah ? menu.status : 'aktif';
    document.getElementById('menu-tersedia').checked = ubah ? menu.tersedia : true;
    document.getElementById('judul-menu').textContent = ubah ? 'Ubah Menu' : 'Tambah Menu';
    bukaModal('modal-menu');
}
</script>
@endpush
