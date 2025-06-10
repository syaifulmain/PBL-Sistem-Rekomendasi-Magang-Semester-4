<?php

namespace Database\Seeders\transactionals;

use App\Enums\LevelTeknis;
use Carbon\Carbon;
use Database\Seeders\SeederCounts;
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

        $listPeriodeMagang = DB::table('m_periode_magang')->select('id', 'tanggal_mulai', 'tanggal_selesai')->get();

        $listDokumenWajibID = DB::table('m_jenis_dokumen')->where('default', 1)->pluck('id');

        $listBidangKeahlianID = DB::table('m_bidang_keahlian')->pluck('id');

        $listKeahlianTeknisID = DB::table('m_keahlian_teknis')->pluck('id');

        $faker = Faker::create('id_ID');

        $perusahaanData = [];

        foreach ($listPerusahaanID as $perusahaan_id) {
            $perusahaanData[$perusahaan_id] = [
                'judul' => $faker->sentence(3),
                'deskripsi' => $faker->paragraph(3),
                'persyaratan' => $faker->paragraph(2),
                'kuota' => $faker->numberBetween(1, 5),
            ];
        }

        $totalPeriode = count($listPeriodeMagang);
        $periodeIndex = 0;

        foreach ($listPeriodeMagang as $periode) {
            $periodeIndex++;
            $isLastPeriode = ($periodeIndex == $totalPeriode);

            $tanggalMulai = Carbon::parse($periode->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($periode->tanggal_selesai);

            foreach ($listPerusahaanID as $perusahaan_id) {
                $data = $perusahaanData[$perusahaan_id];

                $status = $isLastPeriode ? 'TUTUP' : $faker->randomElement(['TUTUP', 'DIBATALKAN']);

                $lowongan_magang_id = DB::table('t_lowongan_magang')->insertGetId([
                    'perusahaan_id' => $perusahaan_id,
                    'periode_magang_id' => $periode->id,
                    'judul' => $data['judul'],
                    'deskripsi' => $data['deskripsi'],
                    'persyaratan' => $data['persyaratan'],
                    'kuota' => $data['kuota'],
                    'minimal_ipk' => rand(0, 4),
                    'insentif' => rand(500000, 5000000),
                    'status' => $status,
                    'tanggal_mulai_daftar' => $tanggalMulai,
                    'tanggal_selesai_daftar' => $tanggalMulai->copy()->addDays(14),
                    'tanggal_mulai_magang' => $tanggalMulai->copy()->addDays(15),
                    'tanggal_selesai_magang' => $tanggalSelesai,
                ]);

                foreach ($listDokumenWajibID as $jenis_dokumen_id) {
                    DB::table('t_dokumen_lowongan_magang')->insert([
                        'lowongan_magang_id' => $lowongan_magang_id,
                        'jenis_dokumen_id' => $jenis_dokumen_id,
                    ]);
                }

                for ($i = 0; $i < SeederCounts::KEAHLIAN_LOWONGAN_MAGANG; $i++) {
                    DB::table('t_keahlian_lowongan_kerja')->insert([
                        'lowongan_magang_id' => $lowongan_magang_id,
                        'bidang_keahlian_id' => $listBidangKeahlianID->random(),
                    ]);
                }

                for ($i = 0; $i < SeederCounts::KEAHLIAN_TEKNIS_MAGANG; $i++) {
                    DB::table('t_keahlian_teknis_lowongan')->insert([
                        'lowongan_magang_id' => $lowongan_magang_id,
                        'keahlian_teknis_id' => $listKeahlianTeknisID->random(),
                        'level' => $faker->randomElement(LevelTeknis::cases()),
                    ]);
                }
            }
        }
    }
}
