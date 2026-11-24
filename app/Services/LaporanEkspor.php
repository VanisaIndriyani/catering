<?php

namespace App\Services;

use App\Support\Status;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanEkspor
{
    public function excel(string $jenis, Carbon $awal, Carbon $akhir, array $hasil): StreamedResponse
    {
        $judul = $this->judul($jenis);
        $baris = $this->baris($jenis, $hasil);
        $kolom = count($baris[0]);
        $akhirKolom = chr(64 + $kolom);

        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle($jenis === 'transaksi' ? 'Transaksi' : 'Pesanan');

        $sheet->mergeCells("A1:{$akhirKolom}1");
        $sheet->setCellValue('A1', 'Catering Calakan — '.$judul);
        $sheet->mergeCells("A2:{$akhirKolom}2");
        $sheet->setCellValue('A2', 'Periode '.$awal->translatedFormat('d F Y').' – '.$akhir->translatedFormat('d F Y'));
        $sheet->setCellValue('A4', 'Jumlah data');
        $sheet->setCellValue('B4', $hasil['jumlah']);
        $sheet->setCellValue('A5', $jenis === 'transaksi' ? 'Nilai terverifikasi' : 'Total pesanan');
        $sheet->setCellValue('B5', (float) $hasil['total']);

        $mulai = 7;
        $sheet->fromArray($baris, null, 'A'.$mulai);
        $terakhir = $mulai + count($baris) - 1;

        $sheet->getStyle("A1:{$akhirKolom}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Calibri'],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '14532D']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle("A2:{$akhirKolom}2")->applyFromArray([
            'font' => ['size' => 11, 'color' => ['rgb' => '1F3D2E'], 'italic' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F7F3EC']],
        ]);
        $sheet->getStyle('A4:A5')->getFont()->setBold(true);
        $sheet->getStyle('B5')->getNumberFormat()->setFormatCode('"Rp" #,##0');
        $sheet->getStyle("A{$mulai}:{$akhirKolom}{$mulai}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F3D2E']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle("A{$mulai}:{$akhirKolom}{$terakhir}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E7E0D4']]],
        ]);
        if ($terakhir > $mulai) {
            for ($i = $mulai + 1; $i <= $terakhir; $i++) {
                if (($i - $mulai) % 2 === 0) {
                    $sheet->getStyle("A{$i}:{$akhirKolom}{$i}")
                        ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F7F3EC');
                }
            }
            $angka = $jenis === 'transaksi' ? 'D' : 'D';
            $sheet->getStyle("{$angka}".($mulai + 1).":{$angka}{$terakhir}")
                ->getNumberFormat()->setFormatCode('"Rp" #,##0');
        }
        $sheet->getStyle("A{$mulai}:{$akhirKolom}{$terakhir}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        foreach (range('A', $akhirKolom) as $kol) {
            $sheet->getColumnDimension($kol)->setAutoSize(true);
        }
        $sheet->freezePane('A'.($mulai + 1));
        $sheet->setAutoFilter("A{$mulai}:{$akhirKolom}{$terakhir}");

        $nama = $this->namaBerkas($jenis, $awal, $akhir, 'xlsx');

        return response()->streamDownload(function () use ($book) {
            $writer = new Xlsx($book);
            $writer->save('php://output');
        }, $nama, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function pdf(string $jenis, Carbon $awal, Carbon $akhir, array $hasil)
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('laporan.pdf', [
            'jenis' => $jenis,
            'awal' => $awal,
            'akhir' => $akhir,
            'hasil' => $hasil,
            'judul' => $this->judul($jenis),
            'baris' => $this->baris($jenis, $hasil),
            'logo' => $this->logo(),
        ])->render());
        $dompdf->setPaper('A4');
        $dompdf->render();

        $nama = $this->namaBerkas($jenis, $awal, $akhir, 'pdf');

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$nama.'"',
        ]);
    }

    private function judul(string $jenis): string
    {
        return $jenis === 'transaksi' ? 'Laporan Transaksi' : 'Laporan Pesanan';
    }

    private function namaBerkas(string $jenis, Carbon $awal, Carbon $akhir, string $ext): string
    {
        return 'laporan-'.$jenis.'-'.$awal->format('Ymd').'-'.$akhir->format('Ymd').'.'.$ext;
    }

    private function logo(): ?string
    {
        foreach (['jpeg', 'jpg', 'png'] as $ext) {
            $path = public_path("img/logo.$ext");
            if (is_file($path)) {
                $jenis = $ext === 'png' ? 'png' : 'jpeg';

                return 'data:image/'.$jenis.';base64,'.base64_encode((string) file_get_contents($path));
            }
        }

        return null;
    }

    private function baris(string $jenis, array $hasil): array
    {
        if ($jenis === 'transaksi') {
            $tabel = [['Pesanan', 'Pelanggan', 'Metode', 'Jumlah', 'Status', 'Tanggal']];
            foreach ($hasil['rows'] as $item) {
                $tabel[] = [
                    $item->pesanan->no_pesanan ?? '-',
                    $item->pesanan->pelanggan->nama ?? '-',
                    Status::label('metode', $item->metode),
                    (float) $item->jumlah,
                    Status::label('bayar', $item->status_verifikasi),
                    $item->created_at->translatedFormat('d M Y'),
                ];
            }

            return count($tabel) === 1 ? [...$tabel, ['Tidak ada transaksi pada periode ini.', '', '', '', '', '']] : $tabel;
        }

        $tabel = [['Nomor', 'Pelanggan', 'Tanggal', 'Total', 'Status']];
        foreach ($hasil['rows'] as $item) {
            $tabel[] = [
                $item->no_pesanan,
                $item->pelanggan->nama ?? '-',
                $item->tanggal_pesan->translatedFormat('d M Y'),
                (float) $item->total_harga,
                Status::label('pesanan', $item->status_pesanan),
            ];
        }

        return count($tabel) === 1 ? [...$tabel, ['Tidak ada pesanan pada periode ini.', '', '', '', '']] : $tabel;
    }
}
