<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Services\LaporanEkspor;
use App\Services\LaporanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaporanController extends Controller
{
    public function index(Request $request, LaporanService $service)
    {
        return view('laporan.index', $this->siapkan($request, $service, true));
    }

    public function siapkan(Request $request, LaporanService $service, bool $bisaSimpan): array
    {
        [$awal, $akhir, $jenis] = $this->periode($request);

        return [
            'awal' => $awal,
            'akhir' => $akhir,
            'jenis' => $jenis,
            'hasil' => $service->susun($jenis, $awal, $akhir),
            'tersimpan' => Laporan::with('pembuat')->latest()->take(8)->get(),
            'bisaSimpan' => $bisaSimpan,
            'aksi' => $bisaSimpan ? route('admin.laporan.index') : route('pengelola.laporan.index'),
            'unduhExcel' => $bisaSimpan ? route('admin.laporan.excel') : route('pengelola.laporan.excel'),
            'unduhPdf' => $bisaSimpan ? route('admin.laporan.pdf') : route('pengelola.laporan.pdf'),
        ];
    }

    public function simpan(Request $request, LaporanService $service)
    {
        [$awal, $akhir, $jenis] = $this->periode($request);
        $hasil = $service->susun($jenis, $awal, $akhir);

        Laporan::create([
            'id_pengguna' => $request->user()->id_pengguna,
            'jenis_laporan' => $jenis,
            'periode_awal' => $awal->toDateString(),
            'periode_akhir' => $akhir->toDateString(),
            'total_nilai' => $hasil['total'],
            'jumlah_data' => $hasil['jumlah'],
            'ringkasan' => $hasil['kelompok'],
        ]);

        return redirect()
            ->route('admin.laporan.index', $request->only('dari', 'sampai', 'jenis'))
            ->with('success', 'Laporan disimpan.');
    }

    public function excel(Request $request, LaporanService $service, LaporanEkspor $ekspor)
    {
        [$awal, $akhir, $jenis] = $this->periode($request);

        return $ekspor->excel($jenis, $awal, $akhir, $service->susun($jenis, $awal, $akhir));
    }

    public function pdf(Request $request, LaporanService $service, LaporanEkspor $ekspor)
    {
        [$awal, $akhir, $jenis] = $this->periode($request);

        return $ekspor->pdf($jenis, $awal, $akhir, $service->susun($jenis, $awal, $akhir));
    }

    private function periode(Request $request): array
    {
        $data = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
            'jenis' => ['nullable', Rule::in(['pesanan', 'transaksi'])],
        ]);

        $awal = Carbon::parse($data['dari'] ?? now()->startOfMonth()->toDateString())->startOfDay();
        $akhir = Carbon::parse($data['sampai'] ?? now()->toDateString())->startOfDay();
        if ($akhir->lt($awal)) {
            [$awal, $akhir] = [$akhir, $awal];
        }

        return [$awal, $akhir, $data['jenis'] ?? 'pesanan'];
    }
}
