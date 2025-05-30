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
        $listJenisDokumenID = DB::table('m_jenis_dokumen')->where('default', 1)->pluck('id');
        $listTahunPeriodeGanjil = DB::table('m_periode_magang')->where('semester', 'Ganjil')->select('tanggal_mulai')->get();

        foreach ($listUserIDMahasiswa as $id) {
            $gender = $faker->randomElement(['L', 'P']);
            $firstName = ($gender === 'L') ? $faker->firstNameMale : $faker->firstNameFemale;
            $lastName = $faker->lastName;
            $fullName = $firstName . ' ' . $lastName;
            $address = $faker->address;
            $phoneNumber = $faker->phoneNumber;
            $nim = 2714320000 + $id;
            $angkatan = date('Y', strtotime($listTahunPeriodeGanjil->random()->tanggal_mulai));
            $ipk = $faker->randomFloat(2, 2.5, 4);

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
                    'angkatan' => $angkatan,
                    'jenis_kelamin' => $gender,
                    'alamat' => $address,
                    'no_telepon' => $phoneNumber,
                    'ipk' => $ipk,
                ],
            ]);

            $dokumen = [
                "dokumen_pdf.pdf",
                "dokumen_word.docx",
                "image.jpg",
            ];

            foreach ($listJenisDokumenID as $jenisDokumenID) {
                DB::table('t_dokumen_user')->insert([
                    'user_id' => $id,
                    'jenis_dokumen_id' => $jenisDokumenID,
                    'nama' => $faker->randomElement($dokumen),
                    'path' => 'users/dokumen/'
                ]);
            }
        }
    }
}
