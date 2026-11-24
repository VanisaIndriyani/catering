@extends('layouts.app')
@section('title', $pesanan->no_pesanan)
@section('heading', 'Detail Pesanan')
@section('content')
    @include('partials.rincian-pesanan', ['pesanan' => $pesanan])
    <section class="card no-print">
        <h3>Aksi pelanggan</h3>
        <div class="actions">
            @if ($pesanan->status_pesanan === 'menunggu')
                <x-aksi ikon="batal" label="Batalkan pesanan" onclick="bukaModal('modal-batal')" />
            @endif
            @if ($pesanan->pembayaran && ! in_array($pesanan->status_pesanan, ['ditolak', 'dibatalkan']) && $pesanan->pembayaran->status_verifikasi !== 'terverifikasi' && $pesanan->pembayaran->metode !== 'tunai')
                <x-aksi ikon="unggah" label="Unggah bukti" onclick="bukaModal('modal-bukti')" />
            @endif
        </div>
    </section>
    @if ($pesanan->status_pesanan === 'menunggu')
        <div class="modal" id="modal-batal" hidden>
            <div class="modal-card">
                <h3>Batalkan pesanan?</h3>
                <p>Pesanan {{ $pesanan->no_pesanan }} akan dibatalkan dan tidak diproses dapur.</p>
                <form method="post" action="{{ route('pelanggan.pesanan.batal', $pesanan) }}">
                    @csrf
                    <div class="actions">
                        <button class="btn btn-bad" type="submit">Ya, batalkan</button>
                        <button class="btn btn-line" type="button" onclick="tutupModal('modal-batal')">Kembali</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    @if ($pesanan->pembayaran && $pesanan->pembayaran->metode !== 'tunai')
        <div class="modal" id="modal-bukti" @if(! $errors->has('bukti')) hidden @endif>
            <div class="modal-card">
                <h3>Unggah bukti pembayaran</h3>
                <form class="stack" method="post" action="{{ route('pelanggan.pesanan.bukti', $pesanan) }}" enctype="multipart/form-data">
                    @csrf
                    <input class="plain-input" type="file" name="bukti" accept="image/*" required>
                    <div class="actions">
                        <button class="btn" type="submit">Unggah</button>
                        <button class="btn btn-line" type="button" onclick="tutupModal('modal-bukti')">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
