<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'm_user';

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'password' => 'hashed',
        'level' => UserRole::class,
    ];
}
