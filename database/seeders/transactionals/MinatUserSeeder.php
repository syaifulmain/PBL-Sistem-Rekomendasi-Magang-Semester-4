<?php

namespace Database\Seeders\transactionals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MinatUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listBidangKeahlian = DB::table('m_bidang_keahlian')->pluck('id');
        $listMahasiswaID = DB::table('m_mahasiswa')->pluck('id');
        $listDosenID = DB::table('m_dosen')->pluck('id');

        // Mengambil 3 bidang keahlian secara acak untuk setiap mahasiswa
        foreach ($listMahasiswaID as $id) {
            DB::table('t_minat_mahasiswa')->insert([
                ['mahasiswa_id' => $id, 'bidang_keahlian_id' => $listBidangKeahlian->random()],
                ['mahasiswa_id' => $id, 'bidang_keahlian_id' => $listBidangKeahlian->random()],
                ['mahasiswa_id' => $id, 'bidang_keahlian_id' => $listBidangKeahlian->random()],
            ]);
        }

        // Mengambil 3 bidang keahlian secara acak untuk setiap dosen
        foreach ($listDosenID as $id) {
            DB::table('t_minat_dosen')->insert([
                ['dosen_id' => $id, 'bidang_keahlian_id' => $listBidangKeahlian->random()],
                ['dosen_id' => $id, 'bidang_keahlian_id' => $listBidangKeahlian->random()],
                ['dosen_id' => $id, 'bidang_keahlian_id' => $listBidangKeahlian->random()],
            ]);
        }
    }
}
