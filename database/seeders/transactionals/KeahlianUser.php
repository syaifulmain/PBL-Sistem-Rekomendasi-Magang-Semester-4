<?php

namespace Database\Seeders\transactionals;

use Database\Seeders\SeederCounts;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeahlianUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listKeahlianTeknis = DB::table('m_keahlian_teknis')->pluck('id');
        $listMahasiswaID = DB::table('m_mahasiswa')->pluck('id');

        foreach ($listMahasiswaID as $id) {
            for ($i = 0; $i < SeederCounts::MINAT_USER; $i++) {
                DB::table('t_keahlian_mahasiswa')->insert([
                    'mahasiswa_id' => $id, 'keahlian_teknis_id' => $listKeahlianTeknis->random(), 'level' => rand(1, 3),
                ]);
            }
        }
    }
}
