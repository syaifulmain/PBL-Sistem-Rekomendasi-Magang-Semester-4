<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinatMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 't_minat_mahasiswa';

    public function bidangKeahlian()
    {
        return $this->belongsTo(BidangKeahlianModel::class, 'bidang_keahlian_id');
    }
}
