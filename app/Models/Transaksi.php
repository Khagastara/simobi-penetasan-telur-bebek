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

    protected $table = 'transaksis';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'tgl_transaksi',
        'id_pengepul',
        'id_metode_pembayaran',
    ];

    protected $dates = ['tgl_transaksi'];

    public function pengepul(): BelongsTo
    {
        return $this->belongsTo(Pengepul::class, 'id_pengepul', 'id');
    }

    public function statusTransaksi(): HasMany
    {
        return $this->hasMany(StatusTransaksi::class, 'id_transaksi', 'id');
    }

    public function metodePembayaran(): BelongsTo
    {
        return $this->belongsTo(MetodePembayaran::class, 'id_metode_pembayaran', 'id');
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id');
    }

    public function keuangan(): HasMany
    {
        return $this->hasmany(Keuangan::class, 'id_transaksi', 'id');
    }
}
