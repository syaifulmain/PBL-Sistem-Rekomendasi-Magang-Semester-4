<?php

namespace Database\Seeders\masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeahlianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $data = [
            ['nama' => 'Bahasa Inggris'],
            ['nama' => 'Bahasa Jepang'],
            ['nama' => 'Bahasa Mandarin'],
            ['nama' => 'Bahasa Pemrograman PHP'],
            ['nama' => 'Bahasa Pemrograman JavaScript'],
            ['nama' => 'Bahasa Pemrograman Python'],
            ['nama' => 'Bahasa Pemrograman Java'],
            ['nama' => 'Bahasa Pemrograman C++'],
        ];

        DB::table('m_keahlian_teknis')->insert($data);
    }
}

