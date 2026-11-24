<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nama',
        'email',
        'no_telepon',
        'username',
        'password',
        'peran',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function pelanggan(): HasOne
    {
        return $this->hasOne(Pelanggan::class, 'id_pengguna', 'id_pengguna');
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'id_pengguna', 'id_pengguna');
    }

    public function beranda(): string
    {
        return match ($this->peran) {
            'admin' => route('admin.dashboard'),
            'koki' => route('koki.pesanan.index'),
            'pengelola' => route('pengelola.dashboard'),
            default => route('pelanggan.dashboard'),
        };
    }

    public function labelPeran(): string
    {
        return match ($this->peran) {
            'admin' => 'Admin',
            'koki' => 'Koki / Produksi',
            'pengelola' => 'Pengelola',
            default => 'Pelanggan',
        };
    }
}
