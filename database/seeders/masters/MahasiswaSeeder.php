<?php

namespace Database\Seeders\masters;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $listUserIDMahasiswa = DB::table('m_user')->where('level', 'MAHASISWA')->pluck('id');
        $listProgramStudiID = DB::table('m_program_studi')->pluck('id');

        foreach ($listUserIDMahasiswa as $id) {
            $gender = $faker->randomElement(['L', 'P']);
            $firstName = ($gender === 'L') ? $faker->firstNameMale : $faker->firstNameFemale;
            $lastName = $faker->lastName;
            $fullName = $firstName . ' ' . $lastName;
            $address = $faker->address;
            $phoneNumber = $faker->phoneNumber;
            $nim = 2714320000 + $id;

            DB::update(
                'UPDATE m_user SET username = ? WHERE id = ?',
                [$nim, $id]
            );

            DB::table('m_mahasiswa')->insert([
                [
                    'user_id' => $id,
                    'nim' => $nim,
                    'nama' => $fullName,
                    'program_studi_id' => $listProgramStudiID->random(),
                    'angkatan' => 2023,
                    'jenis_kelamin' => $gender,
                    'alamat' => $address,
                    'no_telepon' => $phoneNumber
                ],
            ]);
        }
    }
}
