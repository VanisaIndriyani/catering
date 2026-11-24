@extends('layouts.app')
@section('title', $pesanan->no_pesanan)
@section('heading', 'Kelola Pesanan')
@section('content')
    @include('partials.rincian-pesanan', ['pesanan' => $pesanan])
    <section class="card no-print">
        <h3>Terima / tolak & ubah status</h3>
        <div class="actions">
            @if ($pesanan->status_pesanan === 'menunggu')
                <form method="post" action="{{ route('admin.pesanan.terima', $pesanan) }}">
                    @csrf
                    <x-aksi ikon="terima" label="Terima pesanan" type="submit" />
                </form>
                <x-aksi ikon="tolak" label="Tolak pesanan" onclick="bukaModal('modal-tolak')" />
            @endif
            <x-aksi ikon="status" label="Ubah status" onclick="bukaModal('modal-status')" />
        </div>
    </section>
    @if ($pesanan->status_pesanan === 'menunggu')
        <div class="modal" id="modal-tolak" hidden>
            <div class="modal-card">
                <h3>Tolak pesanan</h3>
                <form class="stack" method="post" action="{{ route('admin.pesanan.tolak', $pesanan) }}">
                    @csrf
                    <label>Alasan<textarea class="plain-input" name="alasan" placeholder="Alasan penolakan">{{ old('alasan') }}</textarea></label>
                    <div class="actions">
                        <button class="btn btn-bad" type="submit">Tolak</button>
                        <button class="btn btn-line" type="button" onclick="tutupModal('modal-tolak')">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <div class="modal" id="modal-status" @if(! $errors->has('status_pesanan')) hidden @endif>
        <div class="modal-card">
            <h3>Ubah status pesanan</h3>
            <form class="stack" method="post" action="{{ route('admin.pesanan.status', $pesanan) }}">
                @csrf
                <label>Status
                    <select class="plain-input" name="status_pesanan">
                        @foreach (['menunggu','diterima','ditolak','diproses','selesai','dibatalkan'] as $status)
                            <option value="{{ $status }}" @selected(old('status_pesanan', $pesanan->status_pesanan) === $status)>{{ \App\Support\Status::label('pesanan', $status) }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Catatan<input class="plain-input" name="catatan_status" value="{{ old('catatan_status', $pesanan->catatan_status) }}"></label>
                <div class="actions">
                    <button class="btn" type="submit">Simpan</button>
                    <button class="btn btn-line" type="button" onclick="tutupModal('modal-status')">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection
