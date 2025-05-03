<?php

namespace Database\Seeders\masters;

use Database\Seeders\SeederCounts;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');
        $data = [];
        $year = $currentYear;

        if ($currentMonth >= 6) {
            $startWithOdd = true;
        } else {
            $startWithOdd = false;
            $year--;
        }

        for ($i = 0; $i < SeederCounts::PERIODE_MAGANG; $i++) {
            if ($startWithOdd) {
                $data[] = [
                    'nama' => "Semester Ganjil $year",
                    'tanggal_mulai' => "$year-08-01",
                    'tanggal_selesai' => "$year-12-31",
                    'tahun_akademik' => "$year/" . ($year + 1),
                    'semester' => 'Ganjil',
                ];
                $startWithOdd = false;
            } else {
                $data[] = [
                    'nama' => "Semester Genap $year",
                    'tanggal_mulai' => "$year-01-01",
                    'tanggal_selesai' => "$year-05-31",
                    'tahun_akademik' => ($year - 1) . "/$year",
                    'semester' => 'Genap',
                ];
                $startWithOdd = true;
                $year--;
            }
        }

        DB::table('m_periode_magang')->insert(array_reverse($data));
    }
}
