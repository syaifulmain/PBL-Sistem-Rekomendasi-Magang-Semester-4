<?php

namespace Database\Seeders\masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/sql/wilayah.sql');
        $sql = File::get($path);

        DB::unprepared($sql);
    }
}
