<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganMagangModel extends Model
{
    use HasFactory;

    protected $table = 't_lowongan_magang';
    protected $fillable = [
        'perusahaan_id',
        'periode_magang_id',
        'judul',
        'deskripsi',
        'persyaratan',
        'kuota',
        'minimal_ipk',
        'insentif',
        'tanggal_mulai_daftar',
        'tanggal_selesai_daftar',
        'tanggal_mulai_magang',
        'tanggal_selesai_magang',
        'status',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(PerusahaanModel::class, 'perusahaan_id');
    }

    public function periodeMagang()
    {
        return $this->belongsTo(PeriodeMagangModel::class, 'periode_magang_id');
    }

    public function keahlian() {
        return $this->belongsToMany(BidangKeahlianModel::class, 't_keahlian_lowongan_kerja', 'lowongan_magang_id', 'bidang_keahlian_id');
    }

    public function dokumen() {
        return $this->belongsToMany(JenisDokumenModel::class, 't_dokumen_lowongan_magang', 'lowongan_magang_id', 'jenis_dokumen_id');
    }

    public function teknis() {
        return $this->belongsToMany(KeahlianTeknisModel::class, 't_keahlian_teknis_lowongan', 'lowongan_magang_id', 'keahlian_teknis_id')->withPivot('level');
    }
}
