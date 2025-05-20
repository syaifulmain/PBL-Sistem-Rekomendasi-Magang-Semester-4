<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangKeahlianLowonganModel extends Model
{
    use HasFactory;

    protected $table = 't_keahlian_lowongan_kerja';
    protected $fillable = [
        'lowongan_magang_id',
        'bidang_keahlian_id',
    ];
}
