@php
    $bayar = $pesanan->pembayaran;
    $lunas = $bayar && $bayar->status_verifikasi === 'terverifikasi';
    $ditolak = $pesanan->status_pesanan === 'ditolak';
    $batal = $pesanan->status_pesanan === 'dibatalkan';
@endphp
<div class="card">
    <div class="row-between">
        <div>
            <p class="eyebrow">Nomor pesanan</p>
            <h3 style="margin:0">{{ $pesanan->no_pesanan }}</h3>
            <p class="muted">{{ $pesanan->pelanggan->nama }} · butuh {{ $pesanan->tanggal_kebutuhan->translatedFormat('d F Y') }}</p>
        </div>
        <div class="actions">
            <x-badge group="pesanan" :value="$pesanan->status_pesanan" />
            <x-badge group="produksi" :value="$pesanan->status_produksi" />
        </div>
    </div>
    @if ($ditolak || $batal)
        <div class="alert bad" style="margin-top:1rem">{{ $pesanan->catatan_status ?: 'Pesanan tidak dilanjutkan.' }}</div>
    @else
        <div class="steps">
            <div class="step on">Pesanan dibuat</div>
            <div class="step {{ in_array($pesanan->status_pesanan, ['diterima','diproses','selesai']) ? 'on' : '' }}">Konfirmasi admin</div>
            <div class="step {{ $lunas ? 'on' : '' }}">Pembayaran</div>
            <div class="step {{ in_array($pesanan->status_produksi, ['dimasak','siap','selesai']) ? 'on' : '' }}">Dapur</div>
            <div class="step {{ $pesanan->status_pesanan === 'selesai' ? 'on' : '' }}">Selesai</div>
        </div>
    @endif
    @if ($pesanan->catatan)
        <p class="note">Catatan pelanggan: {{ $pesanan->catatan }}</p>
    @endif
    <div class="table-wrap">
        <table class="data">
            <thead>
            <tr><th>Menu</th><th>Porsi</th><th>Harga</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
            @foreach ($pesanan->detail as $item)
                <tr>
                    <td>{{ $item->menu->nama_menu ?? 'Menu dihapus' }}</td>
                    <td>{{ $item->jumlah_porsi }}</td>
                    <td>{{ rupiah($item->harga_satuan) }}</td>
                    <td>{{ rupiah($item->subtotal) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <hr class="soft">
    <div class="total-line"><span>Hitung Total</span><span>{{ rupiah($pesanan->total_harga) }}</span></div>
</div>
@if ($bayar)
    <div class="card">
        <h3>Pembayaran</h3>
        <div class="pay-box">
            <div class="row-between"><span>Metode</span><x-badge group="metode" :value="$bayar->metode" /></div>
            <div class="row-between"><span>Status</span><x-badge group="bayar" :value="$bayar->status_verifikasi" /></div>
            <div class="row-between"><span>Jumlah</span><strong>{{ rupiah($bayar->jumlah) }}</strong></div>
            @if ($bayar->metode === 'transfer')
                <div class="rekening">
                    <p class="rekening-brand">Catering KPN Tut Wuri</p>
                    <p class="rekening-bank">BRI</p>
                    <p class="rekening-nomor">1433 0100 0101 569</p>
                    <p class="rekening-nama">a/n Koperasi Pegawai Negeri Tut Wuri</p>
                </div>
                <p class="note">Transfer sesuai total pesanan, lalu unggah bukti pembayaran.</p>
            @elseif ($bayar->metode === 'qris')
                <p class="note">Bayar melalui QRIS Catering KPN Tut Wuri, lalu unggah tangkapan layar bukti.</p>
            @else
                <p class="note">Pembayaran tunai dicatat admin saat serah terima pesanan.</p>
            @endif
            @if ($bayar->catatan_admin)
                <p class="note">Catatan admin: {{ $bayar->catatan_admin }}</p>
            @endif
            @if ($bayar->bukti_url)
                <x-aksi ikon="lihat" label="Lihat bukti pembayaran" href="{{ $bayar->bukti_url }}" target="_blank" />
            @endif
        </div>
    </div>
@endif
