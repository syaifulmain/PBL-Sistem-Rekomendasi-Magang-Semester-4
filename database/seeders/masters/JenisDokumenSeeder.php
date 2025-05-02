<?php

namespace Database\Seeders\masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listDokumenWajib = [
            'Pakta Integritas',
            'Daftar Riwayat Hidup',
            'KHS/cetak Siakad',
            'KTP',
            'KTM',
            'Surat Izin Orang Tua'
        ];

        foreach ($listDokumenWajib as $dokumen) {
            DB::table('m_jenis_dokumen')->insert([
                'nama' => $dokumen,
                'default' => 1,
            ]);
        }

        $listDokumenLainnya = [
            'Kartu BPJS/Asuransi lainnya',
            'SKTM/KIP Kuliah',
            'Proposal Magang',
        ];

        foreach ($listDokumenLainnya as $dokumen) {
            DB::table('m_jenis_dokumen')->insert([
                'nama' => $dokumen,
                'default' => 0,
            ]);
        }
    }
}
