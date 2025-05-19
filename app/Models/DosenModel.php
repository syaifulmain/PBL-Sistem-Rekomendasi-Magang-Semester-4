<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenModel extends Model
{
    use HasFactory;

    protected $table = 'm_dosen';

    protected $fillable = [
        'user_id',
        'nama',
        'nip'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function minatDosen()
    {
        return $this->hasMany(MinatDosenModel::class, 'dosen_id');
    }

    public function preferensiLokasiDosen()
    {
        return $this->hasMany(preferensiLokasiDosenModel::class, 'dosen_id');
    }

    public function getGenderName()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
