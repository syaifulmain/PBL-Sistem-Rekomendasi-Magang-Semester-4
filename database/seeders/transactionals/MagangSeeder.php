<?php

namespace Database\Seeders\transactionals;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listPengajuanMagangDisetujui = DB::table('t_pengajuan_magang')->select('id', 'lowongan_magang_id')
            ->where('status', 'DISETUJUI')
            ->get();

        $listDosenID = DB::table('m_dosen')->pluck('id');

        $faker = Faker::create('id_ID');

        foreach ($listPengajuanMagangDisetujui as $pengajuanMagang) {
            $dateLowongan = DB::table('t_lowongan_magang')
                ->where('id', $pengajuanMagang->lowongan_magang_id)
                ->select('tanggal_mulai_magang', 'tanggal_selesai_magang')
                ->first();

            $startDate = Carbon::parse($dateLowongan->tanggal_mulai_magang);
            $endDate = Carbon::parse($dateLowongan->tanggal_selesai_magang);

            $magangID = DB::table('t_magang')->insert([
                'pengajuan_magang_id' => $pengajuanMagang->id,
                'dosen_id' => $listDosenID->random(),
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'status' => 'selesai',
            ]);

            while ($startDate->lte($endDate)) {
                $logDate = $startDate->copy();

                DB::table('t_log_magang_mahasiswa')->insert([
                    'magang_id' => $magangID,
                    'tanggal' => $logDate->format('Y-m-d'),
                    'aktivitas' => $faker->paragraph(2),
                    'kendala' => $faker->paragraph(2),
                    'keterangan' => $faker->paragraph(2),
                ]);

                $startDate->addDays(7);
            }

            while ($startDate->lte($endDate)) {
                $logDate = $startDate->copy();

                DB::table('t_evaluasi_bimbingan')->insert([
                    'magang_id' => $magangID,
                    'tanggal_evaluasi' => $logDate->format('Y-m-d'),
                    'catatan' => $faker->paragraph(2),
                ]);

                $startDate->addDays($faker->numberBetween(5, 25));
            }

            DB::table('t_evaluasi_magang_mahasiswa')->insert([
                'magang_id' => $magangID,
                'sertifikat_path' => 'magang/sertifikat/magang_' . $faker->uuid . '.pdf',
                'umpan_balik_mahasiswa' => $faker->paragraph(3),
            ]);
        }
    }
}
