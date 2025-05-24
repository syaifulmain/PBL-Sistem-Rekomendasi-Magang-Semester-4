<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 't_keahlian_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        'keahlian_teknis_id',
        'level',
    ];

    public function keahlianTeknis()
    {
        return $this->belongsTo(KeahlianTeknisModel::class, 'keahlian_teknis_id');
    }

    public function getKeahlianTeknisNameAttribute()
    {
        return $this ? "{$this->keahlianTeknis->nama} : {$this->getLevelName($this->level)}" : 'Tidak ada';
    }

    private function getLevelName($level)
    {
        return match ($level) {
            '1' => 'Dasar',
            '2' => 'Menengah',
            '3' => 'Lanjutan',
            default => '',
        };
    }
}
