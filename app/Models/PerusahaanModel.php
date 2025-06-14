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
        'no_telepon',
        'path_foto_profil'
    ];

    public function lokasi()
    {
        return $this->hasOne(LokasiPerusahaanModel::class, 'perusahaan_id');
    }

    public function getFotoProfilPath()
    {
        if ($this->path_foto_profil && file_exists(storage_path('app/public/' . $this->path_foto_profil))) {
            return asset('storage/' . $this->path_foto_profil);
        } else {
            return asset('images/default-profile-perusahaan.png');
        }
    }
}
