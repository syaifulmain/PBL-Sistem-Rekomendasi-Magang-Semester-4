<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DosenModel extends Model
{
    use HasFactory, SoftDeletes;

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

    public function magang()
    {
        return $this->hasMany(MagangModel::class, 'dosen_id');
    }

    public function minat()
    {
        return $this->hasMany(MinatDosenModel::class, 'dosen_id');
    }

    public function preferensiLokasi()
    {
        return $this->hasMany(preferensiLokasiDosenModel::class, 'dosen_id');
    }

    public function getGenderNameAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getDokumenTambahanAttribute()
    {
        return JenisDokumenModel::where('default', 0)->get();
    }

    public function getDokumenTambahan()
    {
        return DokumenUserModel::where('user_id', $this->user_id)
            ->whereHas('jenisDokumen', function ($query) {
                $query->where('default', 0);
            })->get();
    }
}
