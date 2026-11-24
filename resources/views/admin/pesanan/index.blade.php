@extends('layouts.app')
@section('title', 'Pesanan')
@section('heading', 'Kelola Pesanan')
@section('content')
    <form class="card filters" method="get">
        <input class="plain-input search" type="search" name="q" value="{{ request('q') }}" placeholder="Nomor atau nama pelanggan">
        <select class="plain-input" name="status">
            <option value="">Semua status</option>
            @foreach (['menunggu','diterima','ditolak','diproses','selesai','dibatalkan'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ \App\Support\Status::label('pesanan', $status) }}</option>
            @endforeach
        </select>
        <button class="btn btn-sm" type="submit">Filter</button>
    </form>
    <section class="card">
        <div class="table-wrap">
            <table class="data">
                <thead><tr><th>Nomor</th><th>Pelanggan</th><th>Kebutuhan</th><th>Total</th><th>Status</th><th class="kolom-aksi">Aksi</th></tr></thead>
                <tbody>
                @forelse ($pesanan as $item)
                    <tr>
                        <td>{{ $item->no_pesanan }}</td>
                        <td>{{ $item->pelanggan->nama }}</td>
                        <td>{{ $item->tanggal_kebutuhan->translatedFormat('d M Y') }}</td>
                        <td>{{ rupiah($item->total_harga) }}</td>
                        <td><x-badge group="pesanan" :value="$item->status_pesanan" /></td>
                        <td class="actions"><x-aksi ikon="lihat" label="Kelola pesanan" href="{{ route('admin.pesanan.show', $item) }}" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Tidak ada pesanan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem">{{ $pesanan->links() }}</div>
    </section>
@endsection
