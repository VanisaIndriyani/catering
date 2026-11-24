<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->foreignId('id_pengguna')->constrained('pengguna', 'id_pengguna')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('no_telepon', 20);
            $table->string('alamat_instansi')->nullable();
            $table->timestamps();
        });

        Schema::create('kategori_menu', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 50);
            $table->timestamps();
        });

        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->foreignId('id_kategori')->constrained('kategori_menu', 'id_kategori')->restrictOnDelete();
            $table->string('nama_menu', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->boolean('ketersediaan')->default(true);
            $table->string('status', 20)->default('aktif');
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->cascadeOnDelete();
            $table->string('no_pesanan', 20)->unique();
            $table->date('tanggal_pesan');
            $table->date('tanggal_kebutuhan');
            $table->text('catatan')->nullable();
            $table->text('catatan_status')->nullable();
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->string('status_pesanan', 30)->default('menunggu')->index();
            $table->string('status_produksi', 30)->default('menunggu')->index();
            $table->timestamps();
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->cascadeOnDelete();
            $table->foreignId('id_menu')->constrained('menu', 'id_menu')->restrictOnDelete();
            $table->unsignedInteger('jumlah_porsi');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_pesanan')->unique()->constrained('pesanan', 'id_pesanan')->cascadeOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->date('tanggal_bayar')->nullable();
            $table->string('metode', 30);
            $table->string('bukti_bayar')->nullable();
            $table->string('status_verifikasi', 30)->default('menunggu')->index();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->foreignId('id_pengguna')->constrained('pengguna', 'id_pengguna')->restrictOnDelete();
            $table->string('jenis_laporan', 30);
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->decimal('total_nilai', 14, 2)->default(0);
            $table->unsignedInteger('jumlah_data')->default(0);
            $table->json('ringkasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('kategori_menu');
        Schema::dropIfExists('pelanggan');
    }
};
