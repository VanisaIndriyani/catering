<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $pelanggan = $request->user()->pelanggan;
        abort_unless($pelanggan, 403);

        $pesanan = Pesanan::with('pembayaran')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->latest()
            ->take(4)
            ->get();

        $aktif = Pesanan::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->whereIn('status_pesanan', ['menunggu', 'diterima', 'diproses'])
            ->count();

        $total = Pesanan::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->where('status_pesanan', 'selesai')
            ->sum('total_harga');

        $menu = Menu::with('kategori')->where('status', 'aktif')->where('ketersediaan', true)->take(4)->get();
        $kategori = KategoriMenu::orderBy('id_kategori')->get();

        return view('pelanggan.dashboard', compact('pesanan', 'aktif', 'total', 'menu', 'pelanggan', 'kategori'));
    }
}
