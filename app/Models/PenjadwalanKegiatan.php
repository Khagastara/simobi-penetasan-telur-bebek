<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PenjadwalanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'penjadwalan_kegiatans';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'tgl_penjadwalan',
        'id_owner',
    ];

    protected $dates = ['tgl_penjadwalan'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'id_owner', 'id');
    }

    public function detailPenjadwalan(): HasMany
    {
        return $this->hasMany(DetailPenjadwalan::class, 'id_penjadwalan_kegiatan', 'id');
    }
}
