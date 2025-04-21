<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class DetailPenjadwalan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjadwalans';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'waktu_kegiatan',
        'keterangan',
        'id_penjadwalan',
        'id_status_kegiatan',
    ];

    public function penjadwalanKegiatan(): BelongsTo
    {
        return $this->belongsTo(PenjadwalanKegiatan::class, 'id_penjadwalan', 'id');
    }

    public function statusKegiatan(): BelongsTo
    {
        return $this->belongsTo(StatusKegiatan::class, 'id_status_kegiatan', 'id');
    }
}
