<?php

namespace Database\Seeders\masters;

use Database\Seeders\SeederCounts;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < SeederCounts::PERUSAHAAN; $i++) {
            $name = $faker->company;
            $website = $faker->url;
            $email = $faker->unique()->safeEmail;
            $phoneNumber = $faker->phoneNumber;

            DB::table('m_perusahaan')->insert([
                [
                    'nama' => $name,
                    'website' => $website,
                    'email' => $email,
                    'no_telepon' => $phoneNumber
                ],
            ]);
        }
    }
}
