<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiBimbinganModel extends Model
{
    use HasFactory;

    protected $table = 't_evaluasi_bimbingan';

    protected $fillable = [
        'magang_id',
        'log_magang_mahasiswa_id',
        'tanggal_evaluasi',
        'catatan',
    ];

    public function logMagangMahasiswa()
    {
        return $this->belongsTo(LogMagangMahasiswaModel::class, 'log_magang_mahasiswa_id');
    }
}
