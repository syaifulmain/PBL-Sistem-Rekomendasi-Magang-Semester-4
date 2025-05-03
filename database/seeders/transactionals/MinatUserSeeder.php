<?php

namespace Database\Seeders\transactionals;

use Database\Seeders\SeederCounts;
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

        foreach ($listMahasiswaID as $id) {
            for ($i = 0; $i < SeederCounts::MINAT_USER; $i++) {
                DB::table('t_minat_mahasiswa')->insert([
                    'mahasiswa_id' => $id, 'bidang_keahlian_id' => $listBidangKeahlian->random(),
                ]);
            }
        }

        foreach ($listDosenID as $id) {
            for ($i = 0; $i < SeederCounts::MINAT_USER; $i++) {
                DB::table('t_minat_dosen')->insert([
                    'dosen_id' => $id, 'bidang_keahlian_id' => $listBidangKeahlian->random(),
                ]);
            }
        }
    }
}
