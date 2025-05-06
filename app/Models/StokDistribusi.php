<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StokDistribusi extends Model
{
    use HasFactory;

    protected $table = 'stok_distribusis';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nama_stok',
        'jumlah_stok',
        'harga_stok',
        'deskripsi_stok',
        'gambar_stok',
    ];

    public function detailTransaski(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_stok_distribusi', 'id');
    }
}
