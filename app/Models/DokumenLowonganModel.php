<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenLowonganModel extends Model
{
    use HasFactory;

    protected $table = 't_dokumen_lowongan_magang';
    protected $fillable = [
        'lowongan_magang_id',
        'jenis_dokumen_id',
    ];
}
