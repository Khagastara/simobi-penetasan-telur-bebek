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
        'id_status_transaksi',
        'id_pengepul',
        'id_metode_pembayaran',
        'id_keuangan'
    ];

    protected $casts = [
        'tgl_transaksi' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($transaksi) {
            $tanggal = $transaksi->tgl_transaksi->format('d-m-Y');

            $saldoPemasukkan = $transaksi->detailTransaksi->sum('sub_total');
            $totalPenjualan = $transaksi->detailTransaksi->sum('kuantitas');

            $keuangan = Keuangan::whereDate('tgl_rekapitulasi', $tanggal)->first();

            if ($keuangan) {
                $keuangan->update([
                    'saldo_pemasukkan' => $keuangan->saldo_pemasukkan + $saldoPemasukkan,
                    'total_penjualan' => $keuangan->total_penjualan + $totalPenjualan,
                ]);
            } else {
                Keuangan::create([
                    'tgl_rekapitulasi' => $tanggal,
                    'saldo_pengeluaran' => 0,
                    'saldo_pemasukkan' => $saldoPemasukkan,
                    'total_penjualan' => $totalPenjualan,
                ]);
            }
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

    public function keuangan(): BelongsTo
    {
        return $this->belongsTo(Keuangan::class, 'id_keuangan', 'id');

    }
}
