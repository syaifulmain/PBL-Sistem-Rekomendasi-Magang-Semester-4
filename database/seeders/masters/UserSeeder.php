<?php

namespace Database\Seeders\masters;

use Database\Seeders\SeederCounts;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
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

        for ($i = 0; $i < SeederCounts::ADMIN; $i++) {
            DB::table('m_user')->insert([
                'id' => $id++, 'username' => 'admin' . $i + 1, 'password' => $password, 'level' => 'ADMIN'
            ]);
        }

        for ($i = 0; $i < SeederCounts::DOSEN; $i++) {
            DB::table('m_user')->insert([
                'id' => $id++, 'username' => 'dosen' . $i, 'password' => $password, 'level' => 'DOSEN'
            ]);
        }

        for ($i = 0; $i < SeederCounts::MAHASISWA; $i++) {
            DB::table('m_user')->insert([
                'id' => $id++, 'username' => 'mahasiswa' . $i, 'password' => $password, 'level' => 'MAHASISWA'
            ]);
        }
    }
}
