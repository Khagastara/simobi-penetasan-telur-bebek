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
        'id_status_transaksi',
        'id_pengepul',
        'id_metode_pembayaran',
    ];

    protected $casts = [
        'tgl_transaksi' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($transaksi) {
            $saldoPemasukkan = $transaksi->detailTransaksi->sum('sub_total');

            // Buat data keuangan baru
            \App\Models\Keuangan::create([
                'tgl_rekapitulasi' => $transaksi->tgl_transaksi->format('Y-m-d'),
                'saldo_pengeluaran' => 0,
                'saldo_pemasukkan' => $saldoPemasukkan,
                'total_penjualan' => $transaksi->detailTransaksi->sum('kuantitas'),
                'id_transaksi' => $transaksi->id,
            ]);
        });
    }

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

    public function keuangan(): HasMany
    {
        return $this->hasmany(Keuangan::class, 'id_transaksi', 'id');
    }
}
