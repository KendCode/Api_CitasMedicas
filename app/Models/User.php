<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // 👈 AÑADE ESTO

/**
 * @method \Laravel\Sanctum\NewAccessToken createToken(string $name, array $abilities = [])
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // 👈 AGREGA HasApiTokens AQUÍ

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
