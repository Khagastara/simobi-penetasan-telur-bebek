<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Keuangan;
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
        'snap_token',
        'order_id',
        'payment_status',
        'id_pengepul',
        'id_status_transaksi',
        'id_metode_pembayaran',
        'id_keuangan'
    ];

    protected $casts = [
        'tgl_transaksi' => 'datetime',
    ];

    public function pengepul(): BelongsTo
    {
        return $this->belongsTo(Pengepul::class, 'id_pengepul', 'id');
    }

    public function statusTransaksi(): BelongsTo
    {
        return $this->belongsTo(StatusTransaksi::class, 'id_status_transaksi', 'id');
    }

    public function metodePembayaran(): BelongsTo
    {
        return $this->belongsTo(MetodePembayaran::class, 'id_metode_pembayaran', 'id');
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id');
    }

    public function keuangan(): BelongsTo
    {
        return $this->belongsTo(Keuangan::class, 'id_keuangan', 'id');

    }
}
