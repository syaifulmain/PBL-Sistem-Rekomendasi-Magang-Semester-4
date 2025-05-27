<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPengajuanModel extends Model
{
    use HasFactory;

    protected $table = 't_dokumen_pengajuan';
    protected $fillable = [
        'pengajuan_magang_id',
        'jenis_dokumen_id',
        'path',
    ];
    
    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumenModel::class, 'jenis_dokumen_id');
    }
}
