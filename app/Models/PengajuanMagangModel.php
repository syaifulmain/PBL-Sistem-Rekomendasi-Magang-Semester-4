<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagangModel extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_magang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'mahasiswa_id',
        'lowongan_magang_id',
        'tanggal_pengajuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'no_telepon',
        'email',
        'status',
        'catatan',
        'cv',
        'transkip',
        'ktp',
        'ktm',
        'sertifikat',
        'proposal'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'status' => 'string'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function lowonganMagang()
    {
        return $this->belongsTo(LowonganMagang::class, 'lowongan_magang_id');
    }
}