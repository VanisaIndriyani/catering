<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pesanan',
        'jumlah',
        'tanggal_bayar',
        'metode',
        'bukti_bayar',
        'status_verifikasi',
        'catatan_admin',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_bayar' => 'date',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function getBuktiUrlAttribute(): ?string
    {
        if (! $this->bukti_bayar) {
            return null;
        }

        return asset('storage/'.$this->bukti_bayar);
    }
}
