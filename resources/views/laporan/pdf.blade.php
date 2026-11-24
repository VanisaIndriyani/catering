<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 32px 36px; }
        body { font-family: DejaVu Sans, sans-serif; color: #25352d; font-size: 11px; }
        .banner { background: #14532d; color: #ffffff; padding: 16px 18px 14px; }
        .brand { font-size: 10px; letter-spacing: 1px; color: #e4d3ae; margin: 0 0 4px; }
        h1 { font-size: 18px; margin: 0; }
        .sub { margin: 4px 0 0; font-size: 11px; color: #f7f3ec; }
        .logo { width: 42px; height: 42px; }
        table.meta { width: 100%; border-collapse: collapse; margin-top: 14px; }
        table.meta td { background: #f7f3ec; padding: 8px 10px; width: 33%; }
        table.meta .label { display: block; color: #777777; font-size: 9px; }
        table.meta strong { font-size: 13px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 14px; }
        table.data th { background: #1f3d2e; color: #ffffff; text-align: left; padding: 7px 8px; font-size: 10px; }
        table.data td { padding: 6px 8px; border-bottom: 1px solid #e7e0d4; }
        table.data tr:nth-child(even) td { background: #f7f3ec; }
        .right { text-align: right; }
        .foot { margin-top: 16px; color: #777777; font-size: 9px; }
    </style>
</head>
<body>
    <div class="banner">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:52px; vertical-align:middle;">
                    @if ($logo)
                        <img class="logo" src="{{ $logo }}" alt="">
                    @endif
                </td>
                <td style="vertical-align:middle; color:#ffffff;">
                    <p class="brand">CATERING CALAKAN · BBGTK JABAR</p>
                    <h1>{{ $judul }}</h1>
                    <p class="sub">{{ $awal->translatedFormat('d F Y') }} – {{ $akhir->translatedFormat('d F Y') }}</p>
                </td>
            </tr>
        </table>
    </div>
    <table class="meta">
        <tr>
            <td><span class="label">Jumlah data</span><strong>{{ $hasil['jumlah'] }}</strong></td>
            <td><span class="label">{{ $jenis === 'transaksi' ? 'Nilai terverifikasi' : 'Total pesanan' }}</span><strong>{{ rupiah($hasil['total']) }}</strong></td>
            <td><span class="label">Dicetak</span><strong>{{ now()->translatedFormat('d M Y H:i') }}</strong></td>
        </tr>
    </table>
    <table class="data">
        <thead>
            <tr>
                @foreach ($baris[0] as $kolom)
                    <th @if(in_array($kolom, ['Jumlah', 'Total'], true)) class="right" @endif>{{ $kolom }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach (array_slice($baris, 1) as $barisItem)
                <tr>
                    @foreach ($barisItem as $indeks => $nilai)
                        <td @if(in_array($baris[0][$indeks] ?? '', ['Jumlah', 'Total'], true)) class="right" @endif>
                            {{ is_float($nilai) ? rupiah($nilai) : $nilai }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="foot">Dokumen ini dibuat otomatis dari sistem Catering Calakan.</p>
</body>
</html>
