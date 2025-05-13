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

    protected $fillable = [
        'username',
        'password',
        'level'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'password' => 'hashed',
        'level' => UserRole::class,
    ];

    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'user_id');
    }

    public function dosen()
    {
        return $this->hasOne(DosenModel::class, 'user_id');
    }

    public function mahasiswa()
    {
        return $this->hasOne(MahasiswaModel::class, 'user_id');
    }

    public function getNama()
    {
        if ($this->level === UserRole::ADMIN) {
            return $this->admin ? $this->admin->nama : null;
        } elseif ($this->level === UserRole::DOSEN) {
            return $this->dosen ? $this->dosen->nama : null;
        } elseif ($this->level === UserRole::MAHASISWA) {
            return $this->mahasiswa ? $this->mahasiswa->nama : null;
        }

        return null;
    }
}
