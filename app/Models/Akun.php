<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Akun extends Authenticatable
{
    use HasFactory, Notifiable, HasPushSubscriptions;

    protected $table = 'akuns';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password'
    ];

    public function owner(): HasOne
    {
        return $this->hasOne(Owner::class, 'id_akun', 'id');
    }

    public function pengepul(): HasOne
    {
        return $this->hasOne(Pengepul::class, 'id_akun', 'id');
    }
}
