<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Owner extends Model
{
    use HasFactory;

    protected $table = 'owner';
    protected $primaryKey = 'id_owner';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nama',
        'no_hp',
        'id_akun',
    ];

    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    public function penjadwalanKegiatan(): HasMany
    {
        return $this->hasMany(penjadwalanKegiatan::class, 'id_owner');
    }
}