<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinatDosenModel extends Model
{
    use HasFactory;

    protected $table = 't_minat_dosen';

    public function bidangKeahlian()
    {
        return $this->belongsTo(BidangKeahlianModel::class, 'bidang_keahlian_id');
    }
}
