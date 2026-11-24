@extends('layouts.app')
@section('title', 'Transaksi')
@section('heading', 'Data Transaksi')
@section('content')
    <form class="catalog-bar" method="get">
        <label class="catalog-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l4 4"/></svg>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Nomor pesanan">
        </label>
        <select class="plain-input" name="status">
            <option value="">Semua status</option>
            @foreach (['menunggu','terverifikasi','ditolak'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ \App\Support\Status::label('bayar', $status) }}</option>
            @endforeach
        </select>
        <button class="btn btn-sm" type="submit">Filter</button>
    </form>

    <section class="home-block">
        <div class="dash-head">
            <h3>Pembayaran</h3>
            <span class="muted">{{ $transaksi->total() }} transaksi</span>
        </div>
        <div class="table-wrap">
            <table class="data">
                <thead><tr><th>Pesanan</th><th>Pelanggan</th><th>Metode</th><th>Jumlah</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                @forelse ($transaksi as $item)
                    <tr>
                        <td>{{ $item->pesanan->no_pesanan ?? '-' }}</td>
                        <td>{{ $item->pesanan->pelanggan->nama ?? '-' }}</td>
                        <td><x-badge group="metode" :value="$item->metode" /></td>
                        <td>{{ rupiah($item->jumlah) }}</td>
                        <td><x-badge group="bayar" :value="$item->status_verifikasi" /></td>
                        <td>{{ optional($item->tanggal_bayar)->translatedFormat('d M Y') ?: $item->created_at->translatedFormat('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Tidak ada transaksi.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem">{{ $transaksi->links() }}</div>
    </section>
@endsection
