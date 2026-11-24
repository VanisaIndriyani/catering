<?php

namespace App\Support;

class Status
{
    public const MAP = [
        'pesanan' => [
            'menunggu' => ['Menunggu konfirmasi', 'wait'],
            'diterima' => ['Diterima', 'info'],
            'ditolak' => ['Ditolak', 'bad'],
            'diproses' => ['Sedang diproses', 'info'],
            'selesai' => ['Selesai', 'ok'],
            'dibatalkan' => ['Dibatalkan', 'bad'],
        ],
        'produksi' => [
            'menunggu' => ['Menunggu dapur', 'wait'],
            'dimasak' => ['Sedang dimasak', 'info'],
            'siap' => ['Siap diserahkan', 'ok'],
            'selesai' => ['Produksi selesai', 'ok'],
        ],
        'bayar' => [
            'menunggu' => ['Menunggu verifikasi', 'wait'],
            'terverifikasi' => ['Terverifikasi', 'ok'],
            'ditolak' => ['Bukti ditolak', 'bad'],
        ],
        'metode' => [
            'transfer' => ['Transfer Bank', 'info'],
            'qris' => ['QRIS', 'info'],
            'tunai' => ['Tunai', 'wait'],
        ],
    ];

    public static function label(string $group, ?string $value): string
    {
        return self::MAP[$group][$value][0] ?? (string) $value;
    }

    public static function tone(string $group, ?string $value): string
    {
        return self::MAP[$group][$value][1] ?? 'wait';
    }
}
