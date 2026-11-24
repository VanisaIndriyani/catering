@extends('layouts.app')
@section('title', 'Pelanggan')
@section('heading', 'Kelola Pelanggan')
@section('content')
    <form class="card filters" method="get">
        <input class="plain-input search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau telepon">
        <button class="btn btn-sm" type="submit">Cari</button>
    </form>
    <section class="card">
        <div class="table-wrap">
            <table class="data">
                <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Instansi</th><th class="kolom-aksi">Aksi</th></tr></thead>
                <tbody>
                @forelse ($pelanggan as $item)
                    <tr>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->no_telepon }}</td>
                        <td>{{ $item->alamat_instansi ?: '—' }}</td>
                        <td class="actions">
                            @php($ubahPelanggan = 'ubahPelanggan('.(string) \Illuminate\Support\Js::from(['id' => $item->id_pelanggan, 'nama' => $item->nama, 'email' => $item->email, 'telepon' => $item->no_telepon, 'instansi' => $item->alamat_instansi, 'username' => $item->pengguna->username ?? '']).')')
                            <x-aksi ikon="ubah" label="Ubah pelanggan" :onclick="$ubahPelanggan" />
                            <x-aksi ikon="lihat" label="Detail pelanggan" href="{{ route('admin.pelanggan.show', $item) }}" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Belum ada pelanggan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem">{{ $pelanggan->links() }}</div>
    </section>
    <div class="modal" id="modal-pelanggan" @if(! $errors->hasAny(['nama','email','no_telepon','alamat_instansi','password']) || ! old('pelanggan_id')) hidden @endif>
        <div class="modal-card">
            <h3>Ubah Pelanggan</h3>
            <form id="form-pelanggan" class="stack" method="post" action="{{ old('pelanggan_id') ? route('admin.pelanggan.update', old('pelanggan_id')) : route('admin.pelanggan.index') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="pelanggan_id" id="pelanggan-id" value="{{ old('pelanggan_id') }}">
                <p class="muted" id="pelanggan-user"></p>
                <label>Nama<input class="plain-input" id="pelanggan-nama" name="nama" value="{{ old('nama') }}" required></label>
                <label>Email<input class="plain-input" id="pelanggan-email" type="email" name="email" value="{{ old('email') }}" required></label>
                <label>Telepon<input class="plain-input" id="pelanggan-telepon" name="no_telepon" value="{{ old('no_telepon') }}" required></label>
                <label>Instansi<input class="plain-input" id="pelanggan-instansi" name="alamat_instansi" value="{{ old('alamat_instansi') }}"></label>
                <label>Password baru<input class="plain-input" type="password" name="password" placeholder="Kosongkan jika tidak diubah"></label>
                <div class="actions">
                    <button class="btn" type="submit">Simpan</button>
                    <button class="btn btn-line" type="button" onclick="tutupModal('modal-pelanggan')">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
function ubahPelanggan(item) {
    const form = document.getElementById('form-pelanggan');
    form.action = @js(url('/admin/pelanggan')) + '/' + item.id;
    document.getElementById('pelanggan-id').value = item.id;
    document.getElementById('pelanggan-nama').value = item.nama || '';
    document.getElementById('pelanggan-email').value = item.email || '';
    document.getElementById('pelanggan-telepon').value = item.telepon || '';
    document.getElementById('pelanggan-instansi').value = item.instansi || '';
    document.getElementById('pelanggan-user').textContent = item.username ? 'Username: ' + item.username : '';
    bukaModal('modal-pelanggan');
}
</script>
@endpush
