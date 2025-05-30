<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiMagangMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 't_evaluasi_magang_mahasiswa';

    protected $fillable = [
        'magang_id',
        'sertifikat_path',
        'umpan_balik_mahasiswa'
    ];

    public function getDokumenPath()
    {
        $dokumen = $this->sertifikat_path;

        if ($dokumen) {
            $extension = pathinfo($dokumen, PATHINFO_EXTENSION);

            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                return asset('storage/' . $dokumen);
            } else if (strtolower($extension) === 'pdf') {
                return asset('images/pdf_file_icon.svg');
            } else if (in_array(strtolower($extension), ['doc', 'docx'])) {
                return asset('images/doc_file_icon.svg');
            }
        }
        return null;
    }
}
