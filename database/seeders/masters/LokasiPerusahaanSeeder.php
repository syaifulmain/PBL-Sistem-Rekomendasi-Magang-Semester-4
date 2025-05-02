<?php

namespace Database\Seeders\masters;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiPerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listProvinsiID = DB::table('m_provinsi')->pluck('id');

        $listPerusahaanID = DB::table('m_perusahaan')->pluck('id');

        foreach ($listPerusahaanID as $id) {
            $provinsi = DB::table('m_provinsi')->select('id', 'nama')->where('id', $listProvinsiID->random())->first();
            $kabupaten = DB::table('m_kabupaten')->select('id', 'provinsi_id', 'nama')->where('provinsi_id', $provinsi->id)->inRandomOrder()->first();
            $kecamatan = DB::table('m_kecamatan')->select('id', 'kabupaten_id', 'nama')->where('kabupaten_id', $kabupaten->id)->inRandomOrder()->first();
            $desa = DB::table('m_desa')->select('id', 'kecamatan_id', 'nama')->where('kecamatan_id', $kecamatan->id)->inRandomOrder()->first();

            DB::table('m_lokasi_perusahaan')->insert([
                'perusahaan_id' => $id,
                'negara_id' => 1,
                'provinsi_id' => $provinsi->id,
                'kabupaten_id' => $kabupaten->id,
                'kecamatan_id' => $kecamatan->id,
                'desa_id' => $desa->id,
            ]);

            DB::table('m_perusahaan')->where('id', $id)->update([
                'alamat' => "{$desa->nama}, {$kecamatan->nama}, {$kabupaten->nama}, {$provinsi->nama}, INDONESIA",
            ]);
        }
    }
}
