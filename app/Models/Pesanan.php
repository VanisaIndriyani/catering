<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'id_pelanggan',
        'no_pesanan',
        'tanggal_pesan',
        'tanggal_kebutuhan',
        'catatan',
        'catatan_status',
        'total_harga',
        'status_pesanan',
        'status_produksi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pesan' => 'date',
            'tanggal_kebutuhan' => 'date',
            'total_harga' => 'decimal:2',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    public function hitungTotal(): float
    {
        return round((float) $this->detail()->sum('subtotal'), 2);
    }
}
