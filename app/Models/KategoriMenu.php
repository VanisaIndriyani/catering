<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriMenu extends Model
{
    protected $table = 'kategori_menu';

    protected $primaryKey = 'id_kategori';

    protected $fillable = ['nama_kategori'];

    public function menu(): HasMany
    {
        return $this->hasMany(Menu::class, 'id_kategori', 'id_kategori');
    }
}
