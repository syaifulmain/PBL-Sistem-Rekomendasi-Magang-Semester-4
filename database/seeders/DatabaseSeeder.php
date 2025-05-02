<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\masters\AdminSeeder;
use Database\Seeders\masters\BidangKeahlianSeeder;
use Database\Seeders\masters\DosenSeeder;
use Database\Seeders\masters\JenisDokumenSeeder;
use Database\Seeders\masters\LokasiPerusahaanSeeder;
use Database\Seeders\masters\MahasiswaSeeder;
use Database\Seeders\masters\PeriodeMagangSeeder;
use Database\Seeders\masters\PerusahaanSeeder;
use Database\Seeders\masters\ProgramStudiSeeder;
use Database\Seeders\masters\UserSeeder;
use Database\Seeders\masters\WilayahSeeder;
use Database\Seeders\transactionals\LowonganMagangSeeder;
use Database\Seeders\transactionals\MinatUserSeeder;
use Database\Seeders\transactionals\PrefrensiLokasiUserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WilayahSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            DosenSeeder::class,
            ProgramStudiSeeder::class,
            MahasiswaSeeder::class,
            JenisDokumenSeeder::class,
            BidangKeahlianSeeder::class,
            PerusahaanSeeder::class,
            LokasiPerusahaanSeeder::class,
            PeriodeMagangSeeder::class,

            MinatUserSeeder::class,
            PrefrensiLokasiUserSeeder::class,
            LowonganMagangSeeder::class,
        ]);
    }
}
