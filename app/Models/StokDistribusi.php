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

    protected $table = 'stok_distribusi';
    protected $primaryKey = 'id_stok_distribusi';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nama_stok',
        'jumlah_stok',
        'harga_stok',
        'deskripsi_stok',
        'gambar_stok',
        'id_detail_transaksi',
    ];

    public function detailTransaski(): BelongsTo
    {
        return $this->belongsTo(DetailTransaksi::class, 'id_detail_transaksi');
    }
}
