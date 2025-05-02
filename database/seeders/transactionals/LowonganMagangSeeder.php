<?php

namespace Database\Seeders\transactionals;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LowonganMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listPerusahaanID = DB::table('m_perusahaan')->pluck('id');

        $lastPeriodeMagang = DB::table('m_periode_magang')->orderBy('id', 'desc')->first();

        $listDokumenWajibID = DB::table('m_jenis_dokumen')->where('default', 1)->pluck('id');

        $listBidangKeahlianID = DB::table('m_bidang_keahlian')->pluck('id');

        $faker = Faker::create('id_ID');

        foreach ($listPerusahaanID as $perusahaan_id) {
            $tanggalMulai = Carbon::parse($lastPeriodeMagang->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($lastPeriodeMagang->tanggal_selesai);

            DB::table('t_lowongan_magang')->insert([
                'perusahaan_id' => $perusahaan_id,
                'periode_magang_id' => $lastPeriodeMagang->id,
                'judul' => $faker->sentence(3),
                'deskripsi' => $faker->paragraph(3),
                'persyaratan' => $faker->paragraph(2),
                'kuota' => $faker->numberBetween(1, 5),
                'status' => 'BUKA',
                'tanggal_mulai_daftar' => $tanggalMulai,
                'tanggal_selesai_daftar' => $tanggalMulai->copy()->addDays(14),
                'tanggal_mulai_magang' => $tanggalMulai->copy()->addDays(15),
                'tanggal_selesai_magang' => $tanggalSelesai,
            ]);

            foreach ($listDokumenWajibID as $jenis_dokumen_id) {
                DB::table('t_dokumen_lowongan_magang')->insert([
                    'lowongan_magang_id' => $perusahaan_id,
                    'jenis_dokumen_id' => $jenis_dokumen_id,
                ]);
            }

            for ($i = 0; $i < 3; $i++) {
                DB::table('t_keahlian_lowongan_kerja')->insert([
                    'lowongan_magang_id' => $perusahaan_id,
                    'bidang_keahlian_id' => $listBidangKeahlianID->random(),
                ]);
            }
        }
    }
}
