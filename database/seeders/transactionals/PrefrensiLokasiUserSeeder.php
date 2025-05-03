<?php

namespace Database\Seeders\transactionals;

use Database\Seeders\SeederCounts;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrefrensiLokasiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listProvinsiID = DB::table('m_provinsi')->pluck('id');
        $listMahasiswaID = DB::table('m_mahasiswa')->pluck('id');
        $listDosenID = DB::table('m_dosen')->pluck('id');

        foreach ($listMahasiswaID as $id) {
            for ($i = 0; $i < SeederCounts::PREFRENSI_LOKASI_USER; $i++) {
                $provinsi = DB::table('m_provinsi')->select('id', 'nama')->where('id', $listProvinsiID->random())->first();
                $kabupaten = DB::table('m_kabupaten')->select('id', 'provinsi_id', 'nama')->where('provinsi_id', $provinsi->id)->inRandomOrder()->first();
                $kecamatan = DB::table('m_kecamatan')->select('id', 'kabupaten_id', 'nama')->where('kabupaten_id', $kabupaten->id)->inRandomOrder()->first();
                $desa = DB::table('m_desa')->select('id', 'kecamatan_id', 'nama')->where('kecamatan_id', $kecamatan->id)->inRandomOrder()->first();

                DB::table('t_prefrensi_lokasi_mahasiswa')->insert([
                    'mahasiswa_id' => $id,
                    'negara_id' => 1,
                    'provinsi_id' => $provinsi->id,
                    'kabupaten_id' => $kabupaten->id,
                    'kecamatan_id' => $kecamatan->id,
                    'desa_id' => $desa->id,
                    'nama_tampilan' => "{$desa->nama}, {$kecamatan->nama}, {$kabupaten->nama}, {$provinsi->nama}, INDONESIA"
                ]);
            }
        }

        foreach ($listDosenID as $id) {
            for ($i = 0; $i < SeederCounts::PREFRENSI_LOKASI_USER; $i++) {
                $provinsi = DB::table('m_provinsi')->select('id', 'nama')->where('id', $listProvinsiID->random())->first();
                $kabupaten = DB::table('m_kabupaten')->select('id', 'provinsi_id', 'nama')->where('provinsi_id', $provinsi->id)->inRandomOrder()->first();
                $kecamatan = DB::table('m_kecamatan')->select('id', 'kabupaten_id', 'nama')->where('kabupaten_id', $kabupaten->id)->inRandomOrder()->first();
                $desa = DB::table('m_desa')->select('id', 'kecamatan_id', 'nama')->where('kecamatan_id', $kecamatan->id)->inRandomOrder()->first();

                DB::table('t_prefrensi_lokasi_dosen')->insert([
                    'dosen_id' => $id,
                    'negara_id' => 1,
                    'provinsi_id' => $provinsi->id,
                    'kabupaten_id' => $kabupaten->id,
                    'kecamatan_id' => $kecamatan->id,
                    'desa_id' => $desa->id,
                    'nama_tampilan' => "{$desa->nama}, {$kecamatan->nama}, {$kabupaten->nama}, {$provinsi->nama}, INDONESIA"
                ]);
            }
        }
    }
}
