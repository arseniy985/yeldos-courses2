<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'provider_id',
        'provider_token',
        'auth_type',
        'role',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [  // ✅ Дұрыс жазылуы
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * Аты мен никнеймді XSS-тен қорғайтын мутаторлар
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
    }

    public function setNicknameAttribute($value)
    {
        $this->attributes['nickname'] = htmlspecialchars(strip_tags($value));
    }

    // JWT әдістері
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
