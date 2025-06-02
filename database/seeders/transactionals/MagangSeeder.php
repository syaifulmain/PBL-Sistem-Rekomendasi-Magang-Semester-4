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
        // Ambil daftar pengajuan magang yang sudah disetujui
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

            $magangID = DB::table('t_magang')->insertGetId([
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
                    'dokumentasi' => 'magang/log/' . $faker->randomElement([
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6',
                            'image'
                        ]) . '.jpg'
                ]);

                $startDate->addDays(7);
            }

            $startDate = Carbon::parse($dateLowongan->tanggal_mulai_magang);
            $endDate = Carbon::parse($dateLowongan->tanggal_selesai_magang);

            while ($startDate->lte($endDate)) {
                $logDate = $startDate->copy();

                DB::table('t_evaluasi_bimbingan')->insert([
                    'magang_id' => $magangID,
                    'tanggal_evaluasi' => $logDate->format('Y-m-d'),
                    'catatan' => $faker->paragraph(5),
                ]);

                $startDate->addDays($faker->numberBetween(5, 25));
            }

            DB::table('t_evaluasi_magang_mahasiswa')->insert([
                'magang_id' => $magangID,
                'sertifikat_path' => 'magang/sertifikat/dokumen_pdf.pdf',
                'umpan_balik_mahasiswa' => $faker->paragraph(3),
            ]);
        }

        // Ambil daftar pengajuan magang yang diajukan dan disetujui
        $listPengajuanMagangDisetujui = DB::table('t_pengajuan_magang')->select('id', 'lowongan_magang_id')
            ->where('status', 'diajukan')
            ->get();

        $listDosenID = DB::table('m_dosen')->pluck('id');

        $faker = Faker::create('id_ID');

        foreach ($listPengajuanMagangDisetujui as $pengajuanMagang) {
//            if ($faker->boolean(50)) {
            DB::table('t_pengajuan_magang')
                ->where('id', $pengajuanMagang->id)
                ->update(['status' => 'disetujui']);

            $dateLowongan = DB::table('t_lowongan_magang')
                ->where('id', $pengajuanMagang->lowongan_magang_id)
                ->select('tanggal_mulai_magang', 'tanggal_selesai_magang')
                ->first();

            $startDate = Carbon::parse($dateLowongan->tanggal_mulai_magang);
            $endDate = Carbon::parse($dateLowongan->tanggal_selesai_magang)->subDays(30);

            $magangID = DB::table('t_magang')->insertGetId([
                'pengajuan_magang_id' => $pengajuanMagang->id,
                'dosen_id' => $listDosenID->random(),
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'status' => 'aktif',
            ]);

            while ($startDate->lte($endDate)) {
                $logDate = $startDate->copy();

                DB::table('t_log_magang_mahasiswa')->insert([
                    'magang_id' => $magangID,
                    'tanggal' => $logDate->format('Y-m-d'),
                    'aktivitas' => $faker->paragraph(2),
                    'kendala' => $faker->paragraph(2),
                    'keterangan' => $faker->paragraph(2),
                    'dokumentasi' => 'magang/log/' . $faker->randomElement([
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6',
                            'image'
                        ]) . '.jpg'
                ]);

                $startDate->addDays(7);
            }

            $listIdLogMagang = DB::table('t_log_magang_mahasiswa')
                ->where('magang_id', $magangID)
                ->pluck('id');

            $startDate = Carbon::parse($dateLowongan->tanggal_mulai_magang);
            $endDate = Carbon::parse($dateLowongan->tanggal_selesai_magang);

            while ($startDate->lte($endDate)) {
                $logDate = $startDate->copy();

                DB::table('t_evaluasi_bimbingan')->insert([
                    'magang_id' => $magangID,
                    'tanggal_evaluasi' => $logDate->format('Y-m-d'),
                    'catatan' => $faker->paragraph(5),
                    'log_magang_mahasiswa_id' => $faker->boolean(70) ? $listIdLogMagang->random() : null,
                ]);

                $startDate->addDays($faker->numberBetween(5, 25));
            }
//            }
        }
    }
}
