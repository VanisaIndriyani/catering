@extends('layouts.plain')
@section('title', 'Syarat & Ketentuan')
@section('content')
    <h2 style="font-size:2.2rem">Syarat & Ketentuan</h2>
    <div class="card" style="text-align:left">
        <p>Catering Calakan melayani pemesanan konsumsi untuk kegiatan di lingkungan BBGTK Jawa Barat.</p>
        <p>Pesanan yang masih menunggu konfirmasi dapat dibatalkan. Setelah diterima admin, perubahan dilakukan dengan menghubungi admin.</p>
        <p>Harga yang berlaku adalah harga saat pesanan disimpan. Pembayaran dapat dilakukan melalui transfer, QRIS, atau tunai.</p>
        <p>Bukti transfer atau QRIS diverifikasi admin sebelum dicatat lunas.</p>
        <a class="btn" href="{{ route('register') }}">Kembali</a>
    </div>
@endsection
