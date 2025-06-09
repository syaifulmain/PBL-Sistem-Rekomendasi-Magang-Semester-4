<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagangModel extends Model
{
    use HasFactory;

    protected $table = 't_pengajuan_magang';
    protected $fillable = [
        'mahasiswa_id',
        'lowongan_magang_id',
        'tanggal_pengajuan',
        'status',
        'catatan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class);
    }

    public function lowongan()
    {
        return $this->belongsTo(LowonganMagangModel::class, 'lowongan_magang_id');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenPengajuanModel::class, 'pengajuan_magang_id');
    }

    public function magang()
    {
        return $this->hasOne(MagangModel::class, 'pengajuan_magang_id');
    }
}
