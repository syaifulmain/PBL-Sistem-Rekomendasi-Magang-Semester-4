<?php

namespace Database\Seeders\masters;

use Database\Seeders\SeederCounts;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SeederCounts::LIST_DOKUMEN_WAJIB as $dokumen) {
            DB::table('m_jenis_dokumen')->insert([
                'nama' => $dokumen,
                'default' => 1,
            ]);
        }

        foreach (SeederCounts::LIST_DOKUMEN_LAINNYA as $dokumen) {
            DB::table('m_jenis_dokumen')->insert([
                'nama' => $dokumen,
                'default' => 0,
            ]);
        }
    }
}
