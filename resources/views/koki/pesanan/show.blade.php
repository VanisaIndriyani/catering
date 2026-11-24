@extends('layouts.app')
@section('title', $pesanan->no_pesanan)
@section('heading', 'Detail Produksi')
@section('content')
    @include('partials.rincian-pesanan', ['pesanan' => $pesanan])
    <section class="home-block">
        <div class="dash-head">
            <div>
                <h3>Status produksi</h3>
                <p class="muted" style="margin:.25rem 0 0">Sekarang: <x-badge group="produksi" :value="$pesanan->status_produksi" /></p>
            </div>
            <x-aksi ikon="status" label="Ubah status produksi" onclick="bukaModal('modal-produksi')" />
        </div>
    </section>
    <div class="modal" id="modal-produksi" hidden>
        <div class="modal-card">
            <h3>Status produksi</h3>
            <form class="stack" method="post" action="{{ route('koki.pesanan.status', $pesanan) }}">
                @csrf
                <label>Status
                    <select class="plain-input" name="status_produksi">
                        @foreach (['menunggu','dimasak','siap','selesai'] as $status)
                            <option value="{{ $status }}" @selected($pesanan->status_produksi === $status)>{{ \App\Support\Status::label('produksi', $status) }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="actions">
                    <button class="btn" type="submit">Simpan</button>
                    <button class="btn btn-line" type="button" onclick="tutupModal('modal-produksi')">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection
