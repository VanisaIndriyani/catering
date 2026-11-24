@extends('layouts.app')
@section('title', 'Laporan')
@section('heading', $jenis === 'transaksi' ? 'Laporan Transaksi' : 'Laporan Pesanan')
@section('content')
    @php
        $query = ['dari' => $awal->toDateString(), 'sampai' => $akhir->toDateString(), 'jenis' => $jenis];
    @endphp
    <form class="catalog-bar no-print" method="get" action="{{ $aksi }}">
        <label class="field-block">Dari
            <input class="plain-input" type="date" name="dari" value="{{ $awal->toDateString() }}">
        </label>
        <label class="field-block">Sampai
            <input class="plain-input" type="date" name="sampai" value="{{ $akhir->toDateString() }}">
        </label>
        <label class="field-block">Jenis
            <select class="plain-input" name="jenis">
                <option value="pesanan" @selected($jenis === 'pesanan')>Laporan pesanan</option>
                <option value="transaksi" @selected($jenis === 'transaksi')>Laporan transaksi</option>
            </select>
        </label>
        <button class="btn btn-sm" type="submit">Tampilkan</button>
        <a class="btn btn-sm" href="{{ $unduhExcel }}?{{ http_build_query($query) }}">Excel</a>
        <a class="btn btn-sm btn-line" href="{{ $unduhPdf }}?{{ http_build_query($query) }}">PDF</a>
    </form>

    <section class="home-stats">
        <article class="home-stat">
            <span class="mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg></span>
            <div>
                <span>Periode</span>
                <strong class="is-text">{{ $awal->translatedFormat('d M') }} – {{ $akhir->translatedFormat('d M Y') }}</strong>
                <small>rentang laporan</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 4h10v16H7z"/><path d="M10 8h4M10 12h4"/></svg></span>
            <div>
                <span>Jumlah data</span>
                <strong>{{ $hasil['jumlah'] }}</strong>
                <small>{{ $jenis === 'transaksi' ? 'transaksi' : 'pesanan' }}</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v18M7 7.5c1.5-2 8.5-2 10 0s-2 5-5 5-6.5 3-5 5 8.5 2 10 0"/></svg></span>
            <div>
                <span>Nilai</span>
                <strong>{{ rupiah($hasil['total']) }}</strong>
                <small>{{ $jenis === 'transaksi' ? 'yang terverifikasi' : 'total pesanan' }}</small>
            </div>
        </article>
        <article class="home-stat">
            <span class="mark soft"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 19V9M12 19V5M19 19v-7"/></svg></span>
            <div>
                <span>Ringkasan</span>
                <div class="track-badges">
                    @forelse ($hasil['kelompok'] as $kunci => $jumlah)
                        <span><x-badge group="{{ $jenis === 'transaksi' ? 'bayar' : 'pesanan' }}" :value="$kunci" /> {{ $jumlah }}</span>
                    @empty
                        <small>Tidak ada data</small>
                    @endforelse
                </div>
            </div>
        </article>
    </section>

    <section class="home-block">
        <div class="dash-head">
            <h3>{{ $jenis === 'transaksi' ? 'Data transaksi' : 'Data pesanan' }}</h3>
        </div>
        <div class="table-wrap">
            <table class="data">
                @if ($jenis === 'transaksi')
                    <thead><tr><th>Pesanan</th><th>Pelanggan</th><th>Metode</th><th>Jumlah</th><th>Status</th><th>Tanggal</th></tr></thead>
                    <tbody>
                    @forelse ($hasil['rows'] as $item)
                        <tr>
                            <td>{{ $item->pesanan->no_pesanan }}</td>
                            <td>{{ $item->pesanan->pelanggan->nama }}</td>
                            <td>{{ \App\Support\Status::label('metode', $item->metode) }}</td>
                            <td>{{ rupiah($item->jumlah) }}</td>
                            <td><x-badge group="bayar" :value="$item->status_verifikasi" /></td>
                            <td>{{ $item->created_at->translatedFormat('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">Tidak ada transaksi pada periode ini.</td></tr>
                    @endforelse
                    </tbody>
                @else
                    <thead><tr><th>Nomor</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse ($hasil['rows'] as $item)
                        <tr>
                            <td>{{ $item->no_pesanan }}</td>
                            <td>{{ $item->pelanggan->nama }}</td>
                            <td>{{ $item->tanggal_pesan->translatedFormat('d M Y') }}</td>
                            <td>{{ rupiah($item->total_harga) }}</td>
                            <td><x-badge group="pesanan" :value="$item->status_pesanan" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">Tidak ada pesanan pada periode ini.</td></tr>
                    @endforelse
                    </tbody>
                @endif
            </table>
        </div>
        @if ($bisaSimpan)
            <form class="no-print" style="margin-top:1rem" method="post" action="{{ route('admin.laporan.store') }}">
                @csrf
                <input type="hidden" name="dari" value="{{ $awal->toDateString() }}">
                <input type="hidden" name="sampai" value="{{ $akhir->toDateString() }}">
                <input type="hidden" name="jenis" value="{{ $jenis }}">
                <button class="btn" type="submit">Simpan Laporan</button>
            </form>
        @endif
    </section>

    <section class="home-block">
        <h3>Laporan tersimpan</h3>
        <div class="track-list">
        @forelse ($tersimpan as $item)
            <a class="track-card" href="{{ $aksi }}?dari={{ $item->periode_awal->toDateString() }}&sampai={{ $item->periode_akhir->toDateString() }}&jenis={{ $item->jenis_laporan }}">
                <div>
                    <strong>{{ $item->jenis_laporan === 'transaksi' ? 'Transaksi' : 'Pesanan' }}</strong>
                    <span class="muted">{{ $item->periode_awal->translatedFormat('d M') }} – {{ $item->periode_akhir->translatedFormat('d M Y') }} · {{ $item->pembuat->nama ?? 'Admin' }}</span>
                </div>
                <strong class="track-total">{{ rupiah($item->total_nilai) }}</strong>
                <div class="track-badges"><span class="muted">{{ $item->jumlah_data }} data</span></div>
            </a>
        @empty
            <p class="muted">Belum ada laporan yang disimpan.</p>
        @endforelse
        </div>
    </section>
@endsection
