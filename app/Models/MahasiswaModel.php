<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'm_mahasiswa';

    protected $fillable = [
        'user_id',
        'nama',
        'nim',
        'program_studi_id',
        'angkatan',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudiModel::class, 'program_studi_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function minatMahasiswa()
    {
        return $this->hasMany(MinatMahasiswaModel::class, 'mahasiswa_id');
    }

    public function preferensiLokasiMahasiswa()
    {
        return $this->hasMany(PreferensiLokasiMahasiswa::class, 'mahasiswa_id');
    }

    public function keahlianMahasiswa()
    {
        return $this->hasMany(KeahlianMahasiswaModel::class, 'mahasiswa_id');
    }

    public function dokumenUser()
    {
        return $this->hasMany(DokumenUserModel::class, 'user_id', 'user_id');
    }

    public function getGenderName()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
