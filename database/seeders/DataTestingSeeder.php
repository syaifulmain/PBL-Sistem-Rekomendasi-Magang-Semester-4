<?php

namespace Database\Seeders;

use App\Enums\LevelTeknis;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Periode Magang
        $data = [
            'nama' => "Semester Ganjil 2025",
            'tanggal_mulai' => "2025-07-01",
            'tanggal_selesai' => "2025-12-31",
            'tahun_akademik' => "2025/" . (2025 + 1),
            'semester' => 'Ganjil',
        ];
        $periodeId = DB::table('m_periode_magang')->insertGetId($data);


        // Lowowongan Perusahaan
        $listPerusahaanID = DB::table('m_perusahaan')->pluck('id');

        $listPeriodeMagang = DB::table('m_periode_magang')->select('id', 'tanggal_mulai', 'tanggal_selesai')->where('id', $periodeId)->get();

        $listDokumenWajibID = DB::table('m_jenis_dokumen')->where('default', 1)->pluck('id');

        $listBidangKeahlianID = DB::table('m_bidang_keahlian')->pluck('id');

        $listKeahlianTeknisID = DB::table('m_keahlian_teknis')->pluck('id');

        $faker = Faker::create('id_ID');

        $perusahaanData = [];

        foreach ($listPerusahaanID as $perusahaan_id) {
            $judulLowongan = $faker->randomElement([
                'Internship Marketing Digital',
                'Magang Content Creator',
                'Lowongan Magang Web Developer',
                'Program Magang Desain Grafis',
                'Kesempatan Magang Social Media Specialist',
                'Internship Analis Data',
                'Magang Admin Keuangan'
            ]);
            $perusahaanData[$perusahaan_id] = [
                'judul' => $judulLowongan,
                'deskripsi' => 'Kami sedang mencari individu yang termotivasi dan bersemangat untuk bergabung dengan tim kami sebagai ' . $judulLowongan,
                'persyaratan' => $faker->randomElement([
                    'Mahasiswa tingkat akhir atau fresh graduate',
                    'Memiliki kemampuan komunikasi yang baik',
                    'Mampu bekerja secara individu maupun tim',
                    'Menguasai dasar-dasar ' . $faker->randomElement(['digital marketing', 'content creation', 'web development', 'graphic design', 'social media management', 'data analysis', 'financial administration']),
                ]),
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

                $status = $isLastPeriode ? 'BUKA' : $faker->randomElement(['TUTUP', 'DIBATALKAN']);

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

        // pengajuan magang
        $listLowonganBuka = DB::table('t_lowongan_magang')->select('id', 'kuota', 'status', 'periode_magang_id', 'tanggal_mulai_daftar')->where('status', 'buka')->get();

        foreach ($listLowonganBuka as $lowonganBuka) {
            $listKeahlianLowonganMagangID = DB::table('t_keahlian_lowongan_kerja')->where('lowongan_magang_id', $lowonganBuka->id)->pluck('bidang_keahlian_id');

            $tahunAkademik = DB::table('m_periode_magang')->where('id', $lowonganBuka->periode_magang_id)->value('tahun_akademik');
            $tahunAkademik = explode('/', $tahunAkademik)[0] - 1;
            $listMahasiswa = DB::table('m_mahasiswa')
                ->select('id', 'user_id')
                ->where('angkatan', $tahunAkademik)
                ->whereNotIn('id', function ($query) {
                    $query->select('tp.mahasiswa_id')
                        ->from('t_magang as tm')
                        ->join('t_pengajuan_magang as tp', 'tm.pengajuan_magang_id', '=', 'tp.id')
                        ->where('tm.status', 'aktif');
                })
                ->inRandomOrder()
                ->get();
            $kuota = $lowonganBuka->kuota;

            $i = 0;
            foreach ($listMahasiswa as $mahasiswa) {

                $existingPengajuan = DB::table('t_pengajuan_magang')
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->whereIn('status', ['diajukan'])
//                    ->whereIn('lowongan_magang_id', $listLowonganBuka->pluck('id'))
                    ->exists();
                if ($existingPengajuan) {
                    continue;
                }

                $listKeahlianMahasiswaID = DB::table('t_minat_mahasiswa')->where('mahasiswa_id', $mahasiswa->id)->pluck('bidang_keahlian_id');
                $listDokumenMahasiswa = DB::table('t_dokumen_user')->where('user_id', $mahasiswa->user_id)->select('jenis_dokumen_id', 'nama')->get();

                $matchingSkills = $listKeahlianMahasiswaID->intersect($listKeahlianLowonganMagangID);
                $pengajuanMagangID = 0;
                if ($matchingSkills->count() > 0) {
                    $pengajuanMagangID = DB::table('t_pengajuan_magang')->insertGetId([
                        'lowongan_magang_id' => $lowonganBuka->id,
                        'mahasiswa_id' => $mahasiswa->id,
                        'status' => 'diajukan',
                        'tanggal_pengajuan' => $lowonganBuka->tanggal_mulai_daftar,
                    ]);
                } else {
                    $pengajuanMagangID = DB::table('t_pengajuan_magang')->insertGetId([
                        'lowongan_magang_id' => $lowonganBuka->id,
                        'mahasiswa_id' => $mahasiswa->id,
                        'status' => 'batal',
                        'tanggal_pengajuan' => $lowonganBuka->tanggal_mulai_daftar,
                    ]);
                }

                foreach ($listDokumenMahasiswa as $dokumenMahasiswa) {
                    DB::table('t_dokumen_pengajuan')->insert([
                        'pengajuan_magang_id' => $pengajuanMagangID,
                        'jenis_dokumen_id' => $dokumenMahasiswa->jenis_dokumen_id,
                        'path' => 'lowongan/dokumen/' . $dokumenMahasiswa->jenis_dokumen_id . '/' . $mahasiswa->id . '/' . $dokumenMahasiswa->nama,
                    ]);
                }
                $i++;

                if ($i == $kuota + 2) {
                    break;
                }
            }
        }
    }
}
