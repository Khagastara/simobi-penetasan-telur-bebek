<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StatusKegiatan extends Model
{
    use HasFactory;

    protected $table = 'status_kegiatans';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nama_status_kegiatan',
        'deskripsi',
    ];

    public function detailPenjadwalan(): HasMany
    {
        return $this->hasMany(DetailPenjadwalan::class, 'id_status_kegiatan', 'id');
    }

    public static function defaultStatusId()
    {
        return self::where('nama_status_kgtn', 'To Do')->value('id');
    }
}
