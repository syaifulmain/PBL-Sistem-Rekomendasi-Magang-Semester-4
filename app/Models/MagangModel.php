<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagangModel extends Model
{
    use HasFactory;

    protected $table = 't_magang';

    protected $fillable = [
        'pengajuan_magang_id',
        'dosen_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id');
    }

    public function mahasiswa()
    {
        return $this->hasOneThrough(MahasiswaModel::class, PengajuanMagangModel::class, 'id', 'id', 'pengajuan_magang_id', 'mahasiswa_id');
    }

    public function evaluasiBimbingan()
    {
        return $this->hasMany(EvaluasiBimbinganModel::class, 'magang_id');
    }

    public function pengajuanMagang()
    {
        return $this->belongsTo(PengajuanMagangModel::class, 'pengajuan_magang_id');
    }

    public function logMagangMahasiswa()
    {
        return $this->hasMany(LogMagangMahasiswaModel::class, 'magang_id');
    }

    public function evaluasiMagangMahasiswa()
    {
        return $this->hasOne(EvaluasiMagangMahasiswaModel::class, 'magang_id');
    }

    public function getSisaWaktuMangangAttribute()
    {
        $tanggalAkhir = Carbon::parse($this->tanggal_selesai);
        $selisih = $tanggalAkhir->diffInDays(now(), true);
        return $selisih;
    }

    public function getWaktuMulaiMagangAttribute()
    {
        $tanggalMulai = Carbon::parse($this->tanggal_mulai);
        $selisih = $tanggalMulai->diffInDays(now(), true);
        return $selisih;
    }

    public function getStatusAttribute()
    {
        $tanggalMulai = Carbon::parse($this->tanggal_mulai);
        $tanggalAkhir = Carbon::parse($this->tanggal_selesai);
        $now = Carbon::now();

        if ($now < $tanggalMulai) {
            return 'belum_dimulai';
        } elseif ($now >= $tanggalMulai && $now <= $tanggalAkhir) {
            return 'aktif';
        } else {
            return 'selesai';
        }
    }
}
