<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';

    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_pesanan',
        'id_menu',
        'jumlah_porsi',
        'harga_satuan',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_porsi' => 'integer',
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (DetailPesanan $detail) {
            $detail->subtotal = round((float) $detail->jumlah_porsi * (float) $detail->harga_satuan, 2);
        });
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function hitungSubtotal(): float
    {
        return round((float) $this->jumlah_porsi * (float) $this->harga_satuan, 2);
    }
}
