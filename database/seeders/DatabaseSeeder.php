<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use App\Models\KategoriMenu;
use App\Models\Menu;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $admin = User::create([
                'nama' => 'Admin Calakan',
                'email' => 'admin@bbgtk.go.id',
                'username' => 'admin',
                'no_telepon' => '0220000001',
                'password' => 'admin123',
                'peran' => 'admin',
            ]);

            User::create([
                'nama' => 'Koki Dapur',
                'email' => 'koki@bbgtk.go.id',
                'username' => 'koki',
                'no_telepon' => '0220000002',
                'password' => 'koki123',
                'peran' => 'koki',
            ]);

            User::create([
                'nama' => 'Pengelola BBGTK',
                'email' => 'pengelola@bbgtk.go.id',
                'username' => 'pengelola',
                'no_telepon' => '0220000003',
                'password' => 'pengelola123',
                'peran' => 'pengelola',
            ]);

            $akunPelanggan = User::create([
                'nama' => 'Siti Rahma',
                'email' => 'pelanggan@gmail.com',
                'username' => 'pelanggan',
                'no_telepon' => '081234567890',
                'password' => 'pelanggan123',
                'peran' => 'pelanggan',
            ]);

            $pelanggan = Pelanggan::create([
                'id_pengguna' => $akunPelanggan->id_pengguna,
                'nama' => $akunPelanggan->nama,
                'email' => $akunPelanggan->email,
                'no_telepon' => $akunPelanggan->no_telepon,
                'alamat_instansi' => 'BBGTK Jawa Barat',
            ]);

            $nasi = KategoriMenu::create(['nama_kategori' => 'Nasi Box']);
            $snack = KategoriMenu::create(['nama_kategori' => 'Snack']);
            $minum = KategoriMenu::create(['nama_kategori' => 'Minuman']);
            $paket = KategoriMenu::create(['nama_kategori' => 'Paket Acara']);

            $daftar = [
                [$nasi, 'Nasi Box Ayam Bakar', 'Ayam bakar bumbu kecap, nasi putih, lalap, dan sambal.', 28000, true, 'ayam-bakar.jpg'],
                [$nasi, 'Nasi Box Ayam Goreng', 'Ayam goreng renyah, nasi putih, tahu tempe, dan sambal.', 25000, true, 'ayam-goreng.jpg'],
                [$nasi, 'Nasi Box Ikan Goreng', 'Ikan goreng bumbu kuning, nasi, lalap, dan sambel terasi.', 30000, true, 'ikan.jpg'],
                [$nasi, 'Nasi Liwet Komplit', 'Nasi liwet, ayam, telur, tempe orek, dan kerupuk.', 35000, true, 'liwet.jpg'],
                [$snack, 'Snack Box Isi 4', 'Risoles, pastel, tahu isi, dan pisang goreng.', 18000, true, 'snack-box.jpg'],
                [$snack, 'Risoles Mayo', 'Risoles isi sayuran dan mayo, digoreng hangat.', 8000, true, 'risoles.jpg'],
                [$minum, 'Es Teh Manis', 'Teh manis dingin, segar untuk pendamping nasi box.', 5000, true, 'es-teh.jpg'],
                [$minum, 'Jus Jeruk', 'Jeruk peras segar tanpa pengawet.', 8000, true, 'jus.jpg'],
                [$minum, 'Jus Alpukat', 'Alpukat kocok dengan susu kental manis.', 10000, false, 'alpukat.jpg'],
                [$paket, 'Paket Prasmanan', 'Hidangan prasmanan per porsi untuk acara besar.', 45000, true, 'prasmanan.jpg'],
                [$paket, 'Paket Rapat', 'Nasi box, snack, dan minuman untuk rapat atau diklat.', 32000, true, 'paket-rapat.jpg'],
            ];

            $sarapan = KategoriMenu::create(['nama_kategori' => 'Paket Breakfast']);
            $makan = KategoriMenu::create(['nama_kategori' => 'Paket Lunch/Dinner']);
            $isiSarapan = [
                'A' => "Nasi Kuning\nTelor Balado\nKering Tempe\nSohun Goreng\nKerupuk dan Sambal\nBubur Ketan Hitam\nTeh Tawar\nAir Mineral",
                'B' => "Nasi Uduk\nAyam Goreng\nOrek Tempe\nBihun Goreng\nKerupuk dan Sambal\nBubur Kacang Hijau\nTeh Tawar\nAir Mineral",
                'C' => "Nasi Goreng\nTelur Mata Sapi\nKwetiau Seafood\nTempe Mendoan\nKerupuk dan Sambal\nBubur Sumsum\nTeh Tawar\nAir Mineral",
                'D' => "Nasi Putih\nSoto Ayam\nTelur Rebus\nTahu Isi\nKerupuk dan Sambal\nBubur Mutiara\nTeh Tawar\nAir Mineral",
                'E' => "Lontong\nAyam Kari\nSambal Goreng Kentang\nAcar Timun\nKerupuk dan Sambal\nBubur Ketan Hitam\nTeh Tawar\nAir Mineral",
                'F' => "Nasi Putih\nAyam Goreng Tepung\nBakmi Goreng\nBakwan Sayur\nKerupuk dan Sambal\nKolak Pisang\nTeh Tawar\nAir Mineral",
            ];
            $isiMakan = [
                'A' => "Nasi Putih\nAyam Bakar Madu\nTahu/Tempe Bacem\nUlukutek Leunca\nKerupuk dan Sambal\nIrisan Buah Segar\nTeh Tawar\nAir Mineral",
                'B' => "Nasi Putih\nKakap 2 Rasa\nCah Kailan Bawang Putih\nSambal Goreng Tempe\nKerupuk dan Sambal\nIrisan Buah Segar\nTeh Tawar\nAir Mineral",
                'C' => "Nasi Putih\nOpor Ayam\nGudeg\nSambal Goreng Krecek\nKerupuk dan Sambal\nIrisan Buah Segar\nTeh Tawar\nAir Mineral",
                'D' => "Nasi Putih\nAyam Goreng Kalasan\nSayur Lodeh\nTahu Cabe Ijo\nKerupuk dan Sambal\nIrisan Buah Segar\nTeh Tawar\nAir Mineral",
                'E' => "Nasi Putih\nAyam Rica Rica\nOseng Toge Tahu\nOrek Tempe\nKerupuk dan Sambal\nIrisan Buah Segar\nTeh Tawar\nAir Mineral",
                'F' => "Nasi Putih\nPesmol Ikan Mas\nCah Kangkung Telur Puyuh\nTahu Sedap Wangi\nKerupuk dan Sambal\nIrisan Buah Segar\nTeh Tawar\nAir Mineral",
                'G' => "Nasi Putih\nAyam Saus Mentega\nCah Sawi Putih Baso\nTempe Mendoan\nKerupuk dan Sambal\nIrisan Buah Segar\nTeh Tawar\nAir Mineral",
                'H' => "Nasi Putih\nIkan Panir Asam Manis\nCapcay\nBalado Kentang\nKerupuk dan Sambal\nIrisan Buah Segar\nTeh Tawar\nAir Mineral",
            ];
            $catatanMinum = "\n\nPrasmanan: air mineral di dispenser. Nasi box: air mineral dalam botol.";
            foreach ($isiSarapan as $kode => $isi) {
                $daftar[] = [$sarapan, 'Paket Breakfast '.$kode, $isi, 45000, true, null];
            }
            foreach ($isiMakan as $kode => $isi) {
                $daftar[] = [$makan, 'Paket Lunch '.$kode, $isi.$catatanMinum, 45000, true, null];
            }

            $menu = [];
            foreach ($daftar as [$kategori, $nama, $deskripsi, $harga, $tersedia, $foto]) {
                $path = $foto ? 'img/menu/'.$foto : null;
                $menu[$nama] = Menu::create([
                    'id_kategori' => $kategori->id_kategori,
                    'nama_menu' => $nama,
                    'deskripsi' => $deskripsi,
                    'harga' => $harga,
                    'ketersediaan' => $tersedia,
                    'status' => 'aktif',
                    'foto' => $path && is_file(public_path($path)) ? $path : null,
                ]);
            }

            $this->buatPesanan(
                $pelanggan,
                'CC-'.now()->format('Ymd').'-001',
                now()->toDateString(),
                now()->addDays(3)->toDateString(),
                'Untuk rapat bidang di aula utama.',
                'menunggu',
                'menunggu',
                [
                    [$menu['Nasi Box Ayam Bakar'], 20],
                    [$menu['Es Teh Manis'], 20],
                ],
                'transfer',
                'menunggu',
                null
            );

            $this->buatPesanan(
                $pelanggan,
                'CC-'.now()->subDay()->format('Ymd').'-002',
                now()->subDay()->toDateString(),
                now()->addDay()->toDateString(),
                'Diklat guru, antar ke ruang serbaguna.',
                'diproses',
                'dimasak',
                [
                    [$menu['Paket Rapat'], 15],
                ],
                'qris',
                'terverifikasi',
                now()->subDay()->toDateString()
            );

            $this->buatPesanan(
                $pelanggan,
                'CC-'.now()->subDays(10)->format('Ymd').'-003',
                now()->subDays(10)->toDateString(),
                now()->subDays(8)->toDateString(),
                null,
                'selesai',
                'selesai',
                [
                    [$menu['Snack Box Isi 4'], 10],
                    [$menu['Jus Jeruk'], 10],
                ],
                'tunai',
                'terverifikasi',
                now()->subDays(8)->toDateString()
            );

            unset($admin);
        });
    }

    private function buatPesanan(Pelanggan $pelanggan, string $nomor, string $tanggalPesan, string $tanggalButuh, ?string $catatan, string $statusPesanan, string $statusProduksi, array $item, string $metode, string $statusBayar, ?string $tanggalBayar): void
    {
        $pesanan = Pesanan::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'no_pesanan' => $nomor,
            'tanggal_pesan' => $tanggalPesan,
            'tanggal_kebutuhan' => $tanggalButuh,
            'catatan' => $catatan,
            'total_harga' => 0,
            'status_pesanan' => $statusPesanan,
            'status_produksi' => $statusProduksi,
        ]);

        foreach ($item as [$menu, $jumlah]) {
            DetailPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'id_menu' => $menu->id_menu,
                'jumlah_porsi' => $jumlah,
                'harga_satuan' => $menu->harga,
            ]);
        }

        $total = $pesanan->hitungTotal();
        $pesanan->update(['total_harga' => $total]);

        Pembayaran::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'jumlah' => $total,
            'tanggal_bayar' => $tanggalBayar,
            'metode' => $metode,
            'status_verifikasi' => $statusBayar,
        ]);
    }
}
