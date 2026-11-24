@extends('layouts.app')
@section('title', $pelanggan->nama)
@section('heading', $pelanggan->nama)
@section('content')
    <div class="split">
        <section class="card">
            <div class="dash-head">
                <h3>Data pelanggan</h3>
                <x-aksi ikon="ubah" label="Ubah pelanggan" onclick="bukaModal('modal-pelanggan-detail')" />
            </div>
            <p><strong>Email</strong><br>{{ $pelanggan->email }}</p>
            <p><strong>Telepon</strong><br>{{ $pelanggan->no_telepon }}</p>
            <p><strong>Instansi</strong><br>{{ $pelanggan->alamat_instansi ?: '—' }}</p>
            <p class="muted">Username: {{ $pelanggan->pengguna->username }}</p>
        </section>
        <section class="card">
            <h3>Pesanan</h3>
            <div class="order-list">
                @forelse ($pelanggan->pesanan as $item)
                    <a class="order-row" href="{{ route('admin.pesanan.show', $item) }}">
                        <div><strong>{{ $item->no_pesanan }}</strong><span class="muted">{{ $item->tanggal_kebutuhan->translatedFormat('d M Y') }}</span></div>
                        <span>{{ rupiah($item->total_harga) }}</span>
                        <x-badge group="pesanan" :value="$item->status_pesanan" />
                    </a>
                @empty
                    <p class="muted">Belum ada pesanan.</p>
                @endforelse
            </div>
        </section>
    </div>
    <div class="modal" id="modal-pelanggan-detail" @unless($errors->any()) hidden @endunless>
        <div class="modal-card">
            <h3>Ubah Pelanggan</h3>
            <form class="stack" method="post" action="{{ route('admin.pelanggan.update', $pelanggan) }}">
                @csrf
                @method('PUT')
                <label>Nama<input class="plain-input" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required></label>
                <label>Email<input class="plain-input" type="email" name="email" value="{{ old('email', $pelanggan->email) }}" required></label>
                <label>Telepon<input class="plain-input" name="no_telepon" value="{{ old('no_telepon', $pelanggan->no_telepon) }}" required></label>
                <label>Instansi<input class="plain-input" name="alamat_instansi" value="{{ old('alamat_instansi', $pelanggan->alamat_instansi) }}"></label>
                <label>Password baru<input class="plain-input" type="password" name="password" placeholder="Kosongkan jika tidak diubah"></label>
                <div class="actions">
                    <button class="btn" type="submit">Simpan</button>
                    <button class="btn btn-line" type="button" onclick="tutupModal('modal-pelanggan-detail')">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection
