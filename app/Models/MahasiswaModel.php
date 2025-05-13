<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'm_mahasiswa';

    protected $fillable = [
        'user_id',
        'nama',
        'nim',
        'program_studi_id',
        'angkatan',
    ];
}
