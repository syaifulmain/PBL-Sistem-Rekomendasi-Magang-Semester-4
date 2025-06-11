<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogMagangMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 't_log_magang_mahasiswa';

    protected $fillable = [
        'magang_id',
        'tanggal',
        'aktivitas',
        'kendala',
        'keterangan',
        'dokumentasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function getDokumenPath()
    {
        if ($this->dokumentasi && file_exists(storage_path('app/public/' . $this->dokumentasi))) {
            return asset('storage/' . $this->dokumentasi);
        } else {
            return null;
        }
    }
}
