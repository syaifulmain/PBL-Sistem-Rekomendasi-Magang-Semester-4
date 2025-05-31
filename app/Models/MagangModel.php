<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagangModel extends Model
{
    use HasFactory;

    protected $table = 't_magang';

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id');
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
        $selisih = $tanggalAkhir->diffInDays(now(), false);
        return $selisih > 0 ? $selisih : 0;
    }
}
