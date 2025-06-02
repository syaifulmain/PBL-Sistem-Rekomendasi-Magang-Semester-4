<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MahasiswaModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_mahasiswa';

    protected $fillable = [
        'user_id',
        'nama',
        'nim',
        'program_studi_id',
        'angkatan',
        'jenis_kelamin',
        'no_telepon',
        'ipk'
    ];

    protected $casts = [
        'ipk' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function minat()
    {
        return $this->hasMany(MinatMahasiswaModel::class, 'mahasiswa_id');
    }

    public function preferensiLokasi()
    {
        return $this->hasMany(PreferensiLokasiMahasiswa::class, 'mahasiswa_id');
    }

    public function keahlian()
    {
        return $this->hasMany(KeahlianMahasiswaModel::class, 'mahasiswa_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudiModel::class, 'program_studi_id');
    }

    public function getProgramStudiNameAttribute()
    {
        return $this->programStudi ? "{$this->programStudi->jenjang} {$this->programStudi->nama}" : 'Tidak ada';
    }

    public function getGenderNameAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getDokumenWajibAttribute()
    {
        return JenisDokumenModel::where('default', 1)->get();
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

    public function getAllMinat()
    {
        return $this->minat->pluck('bidangKeahlian.nama')->toArray();
    }

    public function getAllKeahlian()
    {
        return $this->keahlian->pluck('level', 'keahlianTeknis.nama')->toArray();
    }

    public function getAllCorPreferensiLokasi()
    {
        return $this->preferensiLokasi->map(function ($item) {
            return [
                'nama' => $item->nama_tampilan,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
            ];
        })->toArray();
    }

    public function magang()
    {
        return $this->hasManyThrough(MagangModel::class, PengajuanMagangModel::class, 'mahasiswa_id', 'pengajuan_magang_id', 'id', 'id');
    }
}
