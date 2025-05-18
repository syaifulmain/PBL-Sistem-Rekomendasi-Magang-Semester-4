<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 't_keahlian_mahasiswa';

    public function keahlianTeknis()
    {
        return $this->belongsTo(KeahlianTeknisModel::class, 'keahlian_teknis_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }
}
