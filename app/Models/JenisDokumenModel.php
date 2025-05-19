<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisDokumenModel extends Model
{
    use HasFactory;

    protected $table = 'm_jenis_dokumen';

    public function dokumen()
    {
        return $this->hasMany(DokumenUserModel::class, 'jenis_dokumen_id');
    }

    public function getDokumenPathFromUser(int $userId)
    {
        $dokumen = $this->dokumen()->where('user_id', $userId)->first();

        if ($dokumen) {
            $extension = pathinfo($dokumen->nama, PATHINFO_EXTENSION);

            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                return asset("storage/{$dokumen->path}{$dokumen->nama}");
            } else if (strtolower($extension) === 'pdf') {
                return asset('images/pdf_file_icon.svg');
            } else if (in_array(strtolower($extension), ['doc', 'docx'])) {
                return asset('images/doc_file_icon.svg');
            }
        }
        return null;
    }

    public function getDokumenLabelUser($user_id)
    {
        $dokumen = $this->dokumen()->where('user_id', $user_id)->first();

        if ($dokumen) {
            return $dokumen->label;
        }
        return null;
    }

    public function getDokumenIdUser(int $userId)
    {
        $dokumen = $this->dokumen()->where('user_id', $userId)->first();

        if ($dokumen) {
            return $dokumen->id;
        }
        return null;
    }
}
