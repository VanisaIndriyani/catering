<?php

use App\Http\Controllers\Admin\DasborController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporan;
use App\Http\Controllers\Admin\MenuController as AdminMenu;
use App\Http\Controllers\Admin\PelangganController as AdminPelanggan;
use App\Http\Controllers\Admin\PembayaranController as AdminBayar;
use App\Http\Controllers\Admin\PesananController as AdminPesanan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Koki\ProduksiController;
use App\Http\Controllers\Pelanggan\BerandaController as PelangganBeranda;
use App\Http\Controllers\Pelanggan\MenuController as PelangganMenu;
use App\Http\Controllers\Pelanggan\PesananController as PelangganPesanan;
use App\Http\Controllers\Pengelola\IkhtisarController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'beranda']);
Route::get('/syarat', [AuthController::class, 'syarat'])->name('syarat');
Route::get('/privasi', [AuthController::class, 'privasi'])->name('privasi');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'formMasuk'])->name('login');
    Route::post('/login', [AuthController::class, 'masuk'])->middleware('throttle:10,1');
    Route::get('/daftar', [AuthController::class, 'formDaftar'])->name('register');
    Route::post('/daftar', [AuthController::class, 'daftar']);
    Route::get('/lupa-password', [AuthController::class, 'lupa'])->name('password.request');
    Route::get('/auth/google', [AuthController::class, 'google'])->name('auth.google');
});

Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/keluar', [AuthController::class, 'keluar'])->name('logout');

    Route::middleware('peran:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', [PelangganBeranda::class, 'index'])->name('dashboard');
        Route::get('/menu', [PelangganMenu::class, 'index'])->name('menu.index');
        Route::post('/menu/{menu}/keranjang', [PelangganMenu::class, 'tambah'])->name('menu.tambah');
        Route::patch('/keranjang/{menu}', [PelangganMenu::class, 'ubah'])->name('keranjang.ubah');
        Route::delete('/keranjang/{menu}', [PelangganMenu::class, 'hapus'])->name('keranjang.hapus');
        Route::get('/pesan', [PelangganPesanan::class, 'buat'])->name('pesanan.buat');
        Route::post('/pesan', [PelangganPesanan::class, 'simpan'])->name('pesanan.simpan');
        Route::get('/pesanan', [PelangganPesanan::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [PelangganPesanan::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{pesanan}/bukti', [PelangganPesanan::class, 'bukti'])->name('pesanan.bukti');
        Route::post('/pesanan/{pesanan}/batal', [PelangganPesanan::class, 'batal'])->name('pesanan.batal');
        Route::get('/riwayat', [PelangganPesanan::class, 'riwayat'])->name('riwayat');
    });

    Route::middleware('peran:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DasborController::class, 'index'])->name('dashboard');
        Route::get('/pelanggan', [AdminPelanggan::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/{pelanggan}', [AdminPelanggan::class, 'show'])->name('pelanggan.show');
        Route::put('/pelanggan/{pelanggan}', [AdminPelanggan::class, 'update'])->name('pelanggan.update');
        Route::get('/kategori', [AdminMenu::class, 'kategori'])->name('kategori.index');
        Route::post('/kategori', [AdminMenu::class, 'simpanKategori'])->name('kategori.store');
        Route::put('/kategori/{kategori}', [AdminMenu::class, 'ubahKategori'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [AdminMenu::class, 'hapusKategori'])->name('kategori.destroy');
        Route::get('/menu', [AdminMenu::class, 'index'])->name('menu.index');
        Route::get('/menu/tambah', [AdminMenu::class, 'create'])->name('menu.create');
        Route::post('/menu', [AdminMenu::class, 'store'])->name('menu.store');
        Route::get('/menu/{menu}/ubah', [AdminMenu::class, 'edit'])->name('menu.edit');
        Route::put('/menu/{menu}', [AdminMenu::class, 'update'])->name('menu.update');
        Route::delete('/menu/{menu}', [AdminMenu::class, 'destroy'])->name('menu.destroy');
        Route::get('/pesanan', [AdminPesanan::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [AdminPesanan::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{pesanan}/terima', [AdminPesanan::class, 'terima'])->name('pesanan.terima');
        Route::post('/pesanan/{pesanan}/tolak', [AdminPesanan::class, 'tolak'])->name('pesanan.tolak');
        Route::post('/pesanan/{pesanan}/status', [AdminPesanan::class, 'status'])->name('pesanan.status');
        Route::get('/pembayaran', [AdminBayar::class, 'index'])->name('pembayaran.index');
        Route::post('/pembayaran/{pembayaran}/verifikasi', [AdminBayar::class, 'verifikasi'])->name('pembayaran.verifikasi');
        Route::post('/pembayaran/{pembayaran}/tolak', [AdminBayar::class, 'tolak'])->name('pembayaran.tolak');
        Route::get('/laporan', [AdminLaporan::class, 'index'])->name('laporan.index');
        Route::get('/laporan/excel', [AdminLaporan::class, 'excel'])->name('laporan.excel');
        Route::get('/laporan/pdf', [AdminLaporan::class, 'pdf'])->name('laporan.pdf');
        Route::post('/laporan', [AdminLaporan::class, 'simpan'])->name('laporan.store');
    });

    Route::middleware('peran:koki')->prefix('dapur')->name('koki.')->group(function () {
        Route::get('/', [ProduksiController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [ProduksiController::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{pesanan}/status', [ProduksiController::class, 'status'])->name('pesanan.status');
    });

    Route::middleware('peran:pengelola')->prefix('pengelola')->name('pengelola.')->group(function () {
        Route::get('/', [IkhtisarController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [IkhtisarController::class, 'laporan'])->name('laporan.index');
        Route::get('/laporan/excel', [AdminLaporan::class, 'excel'])->name('laporan.excel');
        Route::get('/laporan/pdf', [AdminLaporan::class, 'pdf'])->name('laporan.pdf');
        Route::get('/transaksi', [IkhtisarController::class, 'transaksi'])->name('transaksi.index');
    });
});
