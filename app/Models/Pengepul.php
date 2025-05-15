<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Pengepul extends Authenticatable
{
    use HasFactory;

    protected $table = 'pengepuls';
    protected $primaryKey = 'id';
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
        return $this->belongsTo(Akun::class, 'id_akun','id');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_pengepul', 'id');
    }
}
