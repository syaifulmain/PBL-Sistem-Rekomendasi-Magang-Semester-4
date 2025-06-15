<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeMagangModel extends Model
{
    use HasFactory;
    protected $table = 'm_periode_magang';
    protected $fillable = [
        'nama', 'tanggal_mulai', 'tanggal_selesai',
        'tahun_akademik', 'semester',
        // 'tanggal_pendaftaran_mulai', 'tanggal_pendaftaran_selesai'
    ];
}
