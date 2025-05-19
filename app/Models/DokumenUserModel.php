<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DokumenUserModel extends Model
{
    use HasFactory;

    protected $table = 't_dokumen_user';

    protected $fillable = [
        'user_id',
        'jenis_dokumen_id',
        'nama',
        'path',
    ];

    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumenModel::class, 'jenis_dokumen_id', 'id');
    }

    public function getJenisDokumenName()
    {
        return $this->jenisDokumen ? $this->jenisDokumen->nama : null;
    }

    public function getDokumenPath()
    {
        if (!$this->nama) {
            return null;
        }

        $extension = pathinfo($this->nama, PATHINFO_EXTENSION);

        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
            return asset("storage/{$this->path}{$this->nama}");
        } else if (strtolower($extension) === 'pdf') {
            return asset('images/pdf_file_icon.svg');
        } else if (in_array(strtolower($extension), ['doc', 'docx'])) {
            return asset('images/doc_file_icon.svg');
        }

        return null;
    }
}
