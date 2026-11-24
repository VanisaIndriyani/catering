@extends('layouts.app')
@section('title', $judul)
@section('heading', $judul)
@section('content')
    <section class="home-block">
        @if ($pesanan->isEmpty())
            <div class="empty-cart">
                <span class="q-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg>
                </span>
                <h3>Belum ada pesanan</h3>
                <p>{{ $keterangan }}</p>
                <a class="btn" href="{{ route('pelanggan.menu.index') }}">Pilih Menu</a>
            </div>
        @else
            <p class="muted" style="margin:0 0 .85rem">{{ $keterangan }}</p>
            <div class="track-list">
                @foreach ($pesanan as $item)
                    <a class="track-card" href="{{ route('pelanggan.pesanan.show', $item) }}">
                        <div>
                            <strong>{{ $item->no_pesanan }}</strong>
                            <span class="muted">Dibuat {{ $item->tanggal_pesan->translatedFormat('d M Y') }} · Butuh {{ $item->tanggal_kebutuhan->translatedFormat('d M Y') }}</span>
                        </div>
                        <strong class="track-total">{{ rupiah($item->total_harga) }}</strong>
                        <div class="track-badges">
                            <x-badge group="pesanan" :value="$item->status_pesanan" />
                            @if($item->pembayaran)
                                <x-badge group="bayar" :value="$item->pembayaran->status_verifikasi" />
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            <div style="margin-top:1rem">{{ $pesanan->links() }}</div>
        @endif
    </section>
@endsection
