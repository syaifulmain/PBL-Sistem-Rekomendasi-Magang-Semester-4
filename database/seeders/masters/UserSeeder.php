<?php

namespace Database\Seeders\masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $id = 1;
        $password = Hash::make('12345678');

        DB::table('m_user')->insert([
            'id' => $id++, 'username' => 'admin', 'password' => $password, 'level' => 'ADMIN',
        ]);

        for ($i = 0; $i < 5; $i++) {
            DB::table('m_user')->insert([
                'id' => $id++, 'username' => 'dosen' . $i, 'password' => $password, 'level' => 'DOSEN'
            ]);
        }

        for ($i = 0; $i < 30; $i++) {
            DB::table('m_user')->insert([
                'id' => $id++, 'username' => 'mahasiswa' . $i, 'password' => $password, 'level' => 'MAHASISWA'
            ]);
        }
    }
}
