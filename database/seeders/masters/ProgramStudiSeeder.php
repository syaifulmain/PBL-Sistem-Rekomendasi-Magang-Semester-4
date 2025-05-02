<?php

namespace Database\Seeders\masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_program_studi')->insert([
            ['kode' => 'D4TI', 'nama' => 'Teknik Informatika', 'jenjang' => 'D4'],
            ['kode' => 'D4SIB', 'nama' => 'Sistem Informasi Bisnis', 'jenjang' => 'D4'],
        ]);
    }
}
