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

    protected $casts = [
        'tgl_penjadwalan' => 'date',
    ];

    protected $dates = ['tgl_penjadwalan'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'id_owner', 'id');
    }

    public function detailPenjadwalan(): HasMany
    {
        return $this->hasMany(DetailPenjadwalan::class, 'id_penjadwalan', 'id');
    }

    public function scopeUpcomingDates($query)
    {
        return $query->where('tgl_penjadwalan', '>=', now())
            ->pluck('tgl_penjadwalan')
            ->unique();
    }
}
