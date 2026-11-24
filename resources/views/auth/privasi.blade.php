@extends('layouts.plain')
@section('title', 'Kebijakan Privasi')
@section('content')
    <h2 style="font-size:2.2rem">Kebijakan Privasi</h2>
    <div class="card" style="text-align:left">
        <p>Data nama, email, nomor telepon, dan instansi dipakai untuk akun, pemesanan, serta konfirmasi pembayaran.</p>
        <p>Bukti pembayaran hanya dapat dilihat oleh pelanggan pemilik pesanan, admin, dan pengelola.</p>
        <p>Data tidak dijual ke pihak lain. Akses internal dibatasi sesuai peran: pelanggan, admin, koki, dan pengelola.</p>
        <a class="btn" href="{{ route('register') }}">Kembali</a>
    </div>
@endsection
