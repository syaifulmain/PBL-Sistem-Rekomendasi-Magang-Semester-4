<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianTeknisLowonganModel extends Model
{
    use HasFactory;

    protected $table = 't_keahlian_teknis_lowongan';
    protected $fillable = [
        'lowongan_magang_id',
        'keahlian_teknis_id',
    ];
}
