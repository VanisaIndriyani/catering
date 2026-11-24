@extends('layouts.app')
@section('title', 'Kategori')
@section('heading', 'Kategori Menu')
@section('content')
    @php($idLama = old('kategori_id'))
    <div class="row-between">
        <p class="muted">Kelompokkan menu agar pelanggan lebih mudah memfilter.</p>
        <x-aksi ikon="tambah" label="Tambah kategori" onclick="siapkanKategori()" />
    </div>
    <section class="card">
        <div class="table-wrap">
            <table class="data">
                <thead><tr><th>Kategori</th><th>Jumlah menu</th><th class="kolom-aksi">Aksi</th></tr></thead>
                <tbody>
                @forelse ($kategori as $item)
                    <tr>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>{{ $item->menu_count }}</td>
                        <td class="actions">
                            @php($ubahKategori = 'siapkanKategori('.(string) \Illuminate\Support\Js::from(['id' => $item->id_kategori, 'nama' => $item->nama_kategori]).')')
                            <x-aksi ikon="ubah" label="Ubah kategori" :onclick="$ubahKategori" />
                            <x-aksi ikon="hapus" label="Hapus kategori" onclick="konfirmasiHapus('{{ route('admin.kategori.destroy', $item) }}', 'Hapus kategori {{ $item->nama_kategori }}?')" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty">Belum ada kategori.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="modal" id="modal-kategori" @if(! $errors->has('nama_kategori')) hidden @endif>
        <div class="modal-card">
            <h3 id="judul-kategori">{{ $idLama ? 'Ubah Kategori' : 'Tambah Kategori' }}</h3>
            <form id="form-kategori" class="stack" method="post" action="{{ $idLama ? route('admin.kategori.update', $idLama) : route('admin.kategori.store') }}">
                @csrf
                <input type="hidden" name="_method" id="kategori-method" value="{{ $idLama ? 'PUT' : 'POST' }}">
                <input type="hidden" name="kategori_id" value="{{ $idLama }}">
                <label>Nama kategori<input class="plain-input" id="kategori-nama" name="nama_kategori" value="{{ old('nama_kategori') }}" required></label>
                <div class="actions">
                    <button class="btn" type="submit">Simpan</button>
                    <button class="btn btn-line" type="button" onclick="tutupModal('modal-kategori')">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
function siapkanKategori(item) {
    const form = document.getElementById('form-kategori');
    const ubah = Boolean(item);
    form.action = ubah ? @js(url('/admin/kategori')) + '/' + item.id : @js(route('admin.kategori.store'));
    document.getElementById('kategori-method').value = ubah ? 'PUT' : 'POST';
    form.querySelector('[name=kategori_id]').value = ubah ? item.id : '';
    document.getElementById('kategori-nama').value = ubah ? item.nama : '';
    document.getElementById('judul-kategori').textContent = ubah ? 'Ubah Kategori' : 'Tambah Kategori';
    bukaModal('modal-kategori');
}
</script>
@endpush
