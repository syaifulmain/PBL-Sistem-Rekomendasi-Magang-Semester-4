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

    protected $casts = [
        'minimal_ipk' => 'float',
        'tanggal_mulai_daftar' => 'date',
        'tanggal_selesai_daftar' => 'date',
        'tanggal_mulai_magang' => 'date',
        'tanggal_selesai_magang' => 'date',
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

    public function bidangKeahlianLowongan()
    {
        return $this->hasMany(BidangKeahlianLowonganModel::class, 'lowongan_magang_id');
    }

    public function keahlianTeknisLowongan()
    {
        return $this->hasMany(KeahlianTeknisLowonganModel::class, 'lowongan_magang_id');
    }



    public function getNamaPerusahaan()
    {
        return $this->perusahaan ? $this->perusahaan->nama : 'Tidak ada';
    }

    public function getKeahlian(): array
    {
        return $this->bidangKeahlianLowongan->pluck('bidangKeahlian.nama')->toArray();
    }

    public function getKeahlianTeknis() :array
    {
        $levelMap = [
            'pemula' => 1,
            'senior' => 2,
            'ahli' => 3,
        ];
        return $this->keahlianTeknisLowongan->mapWithKeys(function ($item) use ($levelMap) {
            $level = $levelMap[strtolower($item->level)] ?? null;
            return [$item->keahlianTeknis->nama => $level];
        })->toArray();
    }

    public function getCorLokasi()
    {
        return [
            'latitude' => $this->perusahaan?->lokasi?->latitude,
            'longitude' => $this->perusahaan?->lokasi?->longitude,
        ];
    }

}
