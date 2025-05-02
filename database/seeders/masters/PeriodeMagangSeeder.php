<?php

namespace Database\Seeders\masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_periode_magang')->insert([
            [
                'nama' => 'Semester Ganjil 2023',
                'tanggal_mulai' => '2023-08-01',
                'tanggal_selesai' => '2023-12-31',
                'tahun_akademik' => '2023/2024',
                'semester' => 'Ganjil',
            ],
            [
                'nama' => 'Semester Genap 2023',
                'tanggal_mulai' => '2024-01-01',
                'tanggal_selesai' => '2024-05-31',
                'tahun_akademik' => '2023/2024',
                'semester' => 'Genap',
            ],
            [
                'nama' => 'Semester Ganjil 2024',
                'tanggal_mulai' => '2024-08-01',
                'tanggal_selesai' => '2024-12-31',
                'tahun_akademik' => '2024/2025',
                'semester' => 'Ganjil',
            ],
            [
                'nama' => 'Semester Genap 2024',
                'tanggal_mulai' => '2025-01-01',
                'tanggal_selesai' => '2025-05-31',
                'tahun_akademik' => '2024/2025',
                'semester' => 'Genap',
            ],
        ]);
    }
}
