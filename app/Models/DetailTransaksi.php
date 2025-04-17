<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_detail_transaksi';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'kuantitas',
        'sub_total',
        'id_transaksi'
    ];

    protected $dates = ['tgl_transaksi'];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Keuangan::class, 'id_transaksi');
    }
    public function stokDistribusi(): HasMany
    {
        return $this->hasMany(StokDistribusi::class, 'id_detail_transaksi');
    }
}
