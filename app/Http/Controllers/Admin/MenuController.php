<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function kategori()
    {
        $kategori = KategoriMenu::withCount('menu')->orderBy('nama_kategori')->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function simpanKategori(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:50', 'unique:kategori_menu,nama_kategori'],
        ]);

        KategoriMenu::create($data);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function ubahKategori(Request $request, KategoriMenu $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:50', Rule::unique('kategori_menu', 'nama_kategori')->ignore($kategori->id_kategori, 'id_kategori')],
        ]);

        $kategori->update($data);

        return back()->with('success', 'Kategori diperbarui.');
    }

    public function hapusKategori(KategoriMenu $kategori)
    {
        if ($kategori->menu()->exists()) {
            return back()->with('error', 'Kategori masih dipakai menu. Pindahkan menunya lebih dulu.');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori dihapus.');
    }

    public function index(Request $request)
    {
        $menu = Menu::with('kategori')
            ->when($request->filled('q'), fn ($q) => $q->where('nama_menu', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('kategori'), fn ($q) => $q->where('id_kategori', $request->integer('kategori')))
            ->orderBy('nama_menu')
            ->paginate(10)
            ->withQueryString();

        $kategori = KategoriMenu::orderBy('nama_kategori')->get();

        return view('admin.menu.index', compact('menu', 'kategori'));
    }

    public function create()
    {
        $kategori = KategoriMenu::orderBy('nama_kategori')->get();

        return view('admin.menu.form', ['menu' => new Menu(['status' => 'aktif', 'ketersediaan' => true]), 'kategori' => $kategori]);
    }

    public function store(Request $request)
    {
        $data = $this->valid($request);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menu', 'public');
        }

        Menu::create($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu baru ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $kategori = KategoriMenu::orderBy('nama_kategori')->get();

        return view('admin.menu.form', compact('menu', 'kategori'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $this->valid($request, $menu);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menu', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->detail()->exists()) {
            $menu->update(['status' => 'nonaktif', 'ketersediaan' => false]);

            return back()->with('info', 'Menu sudah pernah dipesan, jadi hanya dinonaktifkan.');
        }

        $menu->delete();

        return back()->with('success', 'Menu dihapus.');
    }

    private function valid(Request $request, ?Menu $menu = null): array
    {
        $data = $request->validate([
            'id_kategori' => ['required', 'exists:kategori_menu,id_kategori'],
            'nama_menu' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'harga' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['ketersediaan'] = $request->boolean('ketersediaan');
        unset($data['foto']);

        return $data;
    }
}
