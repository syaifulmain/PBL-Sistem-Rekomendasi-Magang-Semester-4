<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPerusahaanModel extends Model
{
    use HasFactory;

    protected $table = 'm_lokasi_perusahaan';

    protected $fillable = [
        'perusahaan_id',
        'negara_id',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'alamat'
    ];

    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float',
    ];
}
