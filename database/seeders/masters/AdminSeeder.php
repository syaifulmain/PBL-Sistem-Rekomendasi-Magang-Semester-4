<?php

namespace Database\Seeders\masters;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $listUserIDAdmin = DB::table('m_user')->where('level', 'ADMIN')->pluck('id');

        foreach ($listUserIDAdmin as $id) {
            DB::table('m_admin')->insert([
                'user_id' => $id, 'nama' => $faker->name,
            ]);
        }
    }
}
