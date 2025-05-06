<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'kuantitas',
        'sub_total',
        'id_transaksi',
        'id_stok_distribusi'
    ];

    protected $dates = ['tgl_transaksi'];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Keuangan::class, 'id_transaksi', 'id');
    }
    public function stokDistribusi(): BelongsTo
    {
        return $this->belongsTo(StokDistribusi::class, 'id_stok_distribusi', 'id');
    }
}
