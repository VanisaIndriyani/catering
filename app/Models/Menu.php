<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menu';

    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_kategori',
        'nama_menu',
        'deskripsi',
        'harga',
        'ketersediaan',
        'status',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'ketersediaan' => 'boolean',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriMenu::class, 'id_kategori', 'id_kategori');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu');
    }

    public function tersedia(): bool
    {
        return $this->status === 'aktif' && $this->ketersediaan;
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }

        if (str_starts_with($this->foto, 'img/')) {
            return is_file(public_path($this->foto)) ? asset($this->foto) : null;
        }

        return asset('storage/'.$this->foto);
    }
}
