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

    protected $table = 'keuangan';
    protected $primaryKey = 'id_keuangan';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'saldo_pemasukan',
        'saldo_pengeluaran',
        'grafik_penjualan',
        'tgl_rekaptulasi',
        'total_penjualan',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->HasMany(Transaksi::class, 'id_keuangan');
    }
}