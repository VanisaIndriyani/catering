@extends('layouts.app')
@section('title', 'Pembayaran')
@section('heading', 'Verifikasi Pembayaran')
@section('content')
    <form class="filters" method="get">
        @foreach (['' => 'Semua', 'menunggu' => 'Menunggu', 'terverifikasi' => 'Terverifikasi', 'ditolak' => 'Ditolak'] as $nilai => $label)
            <a class="chip {{ request('status', '') === $nilai ? 'active' : '' }}" href="{{ route('admin.pembayaran.index', $nilai ? ['status' => $nilai] : []) }}">{{ $label }}</a>
        @endforeach
    </form>
    <section class="card">
        <div class="table-wrap">
            <table class="data">
                <thead><tr><th>Pesanan</th><th>Pelanggan</th><th>Metode</th><th>Jumlah</th><th>Status</th><th class="kolom-aksi">Aksi</th></tr></thead>
                <tbody>
                @foreach ($pembayaran as $item)
                    <tr>
                        <td><a class="link" href="{{ route('admin.pesanan.show', $item->pesanan) }}">{{ $item->pesanan->no_pesanan }}</a></td>
                        <td>{{ $item->pesanan->pelanggan->nama }}</td>
                        <td><x-badge group="metode" :value="$item->metode" /></td>
                        <td>{{ rupiah($item->jumlah) }}</td>
                        <td><x-badge group="bayar" :value="$item->status_verifikasi" /></td>
                        <td class="actions">
                            @if($item->bukti_url)
                                <x-aksi ikon="lihat" label="Lihat bukti" href="{{ $item->bukti_url }}" target="_blank" />
                            @endif
                            @if ($item->status_verifikasi !== 'terverifikasi')
                                <form method="post" action="{{ route('admin.pembayaran.verifikasi', $item) }}">
                                    @csrf
                                    <x-aksi ikon="terima" label="Verifikasi" type="submit" />
                                </form>
                                <x-aksi ikon="tolak" label="Tolak" onclick="tolakBayar('{{ route('admin.pembayaran.tolak', $item) }}', '{{ $item->pesanan->no_pesanan }}')" />
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem">{{ $pembayaran->links() }}</div>
    </section>
    <div class="modal" id="modal-tolak-bayar" @if(! $errors->has('catatan_admin')) hidden @endif>
        <div class="modal-card">
            <h3 id="judul-tolak-bayar">Tolak bukti</h3>
            <form id="form-tolak-bayar" class="stack" method="post" action="{{ url('/admin/pembayaran') }}">
                @csrf
                <label>Alasan<textarea class="plain-input" name="catatan_admin" required>{{ old('catatan_admin') }}</textarea></label>
                <div class="actions">
                    <button class="btn btn-bad" type="submit">Tolak bukti</button>
                    <button class="btn btn-line" type="button" onclick="tutupModal('modal-tolak-bayar')">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
function tolakBayar(url, nomor) {
    document.getElementById('form-tolak-bayar').action = url;
    document.getElementById('judul-tolak-bayar').textContent = 'Tolak bukti ' + nomor;
    bukaModal('modal-tolak-bayar');
}
</script>
@endpush
