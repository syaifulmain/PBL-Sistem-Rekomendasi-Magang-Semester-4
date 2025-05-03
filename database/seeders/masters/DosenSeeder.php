<?php

namespace Database\Seeders\masters;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $listUserIDDosen = DB::table('m_user')->where('level', 'DOSEN')->pluck('id');

        foreach ($listUserIDDosen as $id) {
            $gender = $faker->randomElement(['L', 'P']);
            $firstName = ($gender === 'L') ? $faker->firstNameMale : $faker->firstNameFemale;
            $lastName = $faker->lastName;
            $fullName = $firstName . ' ' . $lastName;
            $address = $faker->address;
            $phoneNumber = $faker->phoneNumber;
            $nip = $faker->unique()->numerify('###############');

            DB::update(
                'UPDATE m_user SET username = ? WHERE id = ?',
                [$nip, $id]
            );

            DB::table('m_dosen')->insert([
                [
                    'user_id' => $id,
                    'nip' => $nip,
                    'nama' => $fullName,
                    'jenis_kelamin' => $gender,
                    'alamat' => $address,
                    'no_telepon' => $phoneNumber
                ],
            ]);
        }
    }
}
