<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $kategori = KategoriMenu::orderBy('nama_kategori')->get();

        $menu = Menu::with('kategori')
            ->where('status', 'aktif')
            ->when($request->filled('kategori'), fn ($q) => $q->where('id_kategori', $request->integer('kategori')))
            ->when($request->filled('q'), fn ($q) => $q->where('nama_menu', 'like', '%'.$request->string('q').'%'))
            ->when($request->boolean('tersedia'), fn ($q) => $q->where('ketersediaan', true))
            ->orderBy('nama_menu')
            ->paginate(12)
            ->withQueryString();

        return view('pelanggan.menu.index', compact('menu', 'kategori'));
    }

    public function tambah(Request $request, Menu $menu)
    {
        if (! $menu->tersedia()) {
            return back()->with('error', 'Menu ini sedang tidak tersedia.');
        }

        $jumlah = max(1, min(500, (int) $request->input('jumlah', 1)));
        $cart = session('cart', []);
        $id = $menu->id_menu;

        if (isset($cart[$id])) {
            $cart[$id]['jumlah'] = min(500, $cart[$id]['jumlah'] + $jumlah);
        } else {
            $cart[$id] = [
                'id_menu' => $id,
                'jumlah' => $jumlah,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', $menu->nama_menu.' masuk ke keranjang.');
    }

    public function ubah(Request $request, Menu $menu)
    {
        $cart = session('cart', []);
        if (! isset($cart[$menu->id_menu])) {
            return back();
        }

        $cart[$menu->id_menu]['jumlah'] = max(1, min(500, (int) $request->input('jumlah', 1)));
        session(['cart' => $cart]);

        return back()->with('success', 'Jumlah porsi diperbarui.');
    }

    public function hapus(Menu $menu)
    {
        $cart = session('cart', []);
        unset($cart[$menu->id_menu]);
        session(['cart' => $cart]);

        return back()->with('success', 'Menu dihapus dari keranjang.');
    }
}
