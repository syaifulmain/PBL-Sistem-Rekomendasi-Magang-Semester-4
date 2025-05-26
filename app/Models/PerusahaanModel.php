<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerusahaanModel extends Model
{
    use HasFactory;

    protected $table = 'm_perusahaan';

    protected $fillable = [
        'nama',
        'alamat',
        'website',
        'email',
        'no_telepon'
    ];

    public function lokasi()
    {
        return $this->hasOne(LokasiPerusahaanModel::class, 'perusahaan_id');
    }
}
