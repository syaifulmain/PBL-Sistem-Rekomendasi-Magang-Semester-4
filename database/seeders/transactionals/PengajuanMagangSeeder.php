<?php

namespace Database\Seeders\transactionals;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listLowonganMagang = DB::table('t_lowongan_magang')->select('id', 'kuota', 'status', 'periode_magang_id', 'tanggal_mulai_daftar')->get();

        $listLowonganTutup = $listLowonganMagang->where('status', 'tutup');

        $listLowonganBuka = $listLowonganMagang->where('status', 'buka');

        foreach ($listLowonganTutup as $lowonganTutup) {
//            $listDokumenLowonganMagangID = DB::table('t_dokumen_lowongan_magang')->where('lowongan_magang_id', $lowonganTutup->id)->pluck('jenis_dokumen_id');
            $listKeahlianLowonganMagangID = DB::table('t_keahlian_lowongan_kerja')->where('lowongan_magang_id', $lowonganTutup->id)->pluck('bidang_keahlian_id');

            $tahunAkademik = DB::table('m_periode_magang')->where('id', $lowonganTutup->periode_magang_id)->value('tahun_akademik');
            $tahunAkademik = explode('/', $tahunAkademik)[0];
            $listMahasiswa = DB::table('m_mahasiswa')
                ->select('id', 'user_id')
                ->where('angkatan', $tahunAkademik)
                ->inRandomOrder()
                ->get();
            $kuota = $lowonganTutup->kuota;

            $i = 0;
            foreach ($listMahasiswa as $mahasiswa) {

                $listKeahlianMahasiswaID = DB::table('t_minat_mahasiswa')->where('mahasiswa_id', $mahasiswa->id)->pluck('bidang_keahlian_id');
                $listDokumenMahasiswa = DB::table('t_dokumen_user')->where('user_id', $mahasiswa->user_id)->select('jenis_dokumen_id', 'nama')->get();

                $matchingSkills = $listKeahlianMahasiswaID->intersect($listKeahlianLowonganMagangID);
                $pengajuanMagangID = 0;
                if ($matchingSkills->count() > 0) {
                    $pengajuanMagangID = DB::table('t_pengajuan_magang')->insertGetId([
                        'lowongan_magang_id' => $lowonganTutup->id,
                        'mahasiswa_id' => $mahasiswa->id,
                        'status' => 'disetujui',
                        'tanggal_pengajuan' => $lowonganTutup->tanggal_mulai_daftar,
                    ]);
                } elseif (rand(0, 1)) {
                    $pengajuanMagangID = DB::table('t_pengajuan_magang')->insertGetId([
                        'lowongan_magang_id' => $lowonganTutup->id,
                        'mahasiswa_id' => $mahasiswa->id,
                        'status' => 'ditolak',
                        'tanggal_pengajuan' => $lowonganTutup->tanggal_mulai_daftar,
                    ]);
                } else {
                    $pengajuanMagangID = DB::table('t_pengajuan_magang')->insertGetId([
                        'lowongan_magang_id' => $lowonganTutup->id,
                        'mahasiswa_id' => $mahasiswa->id,
                        'status' => 'batal',
                        'tanggal_pengajuan' => $lowonganTutup->tanggal_mulai_daftar,
                    ]);
                }

                foreach ($listDokumenMahasiswa as $dokumenMahasiswa) {
                    DB::table('t_dokumen_pengajuan')->insert([
                        'pengajuan_magang_id' => $pengajuanMagangID,
                        'jenis_dokumen_id' => $dokumenMahasiswa->jenis_dokumen_id,
                        'path' => 'lowongan/dokumen/' . $dokumenMahasiswa->nama,
                    ]);
                }
                $i++;

                if ($i == $kuota + 2) {
                    break;
                }
            }
        }

        foreach ($listLowonganBuka as $lowonganBuka) {
            $listKeahlianLowonganMagangID = DB::table('t_keahlian_lowongan_kerja')->where('lowongan_magang_id', $lowonganBuka->id)->pluck('bidang_keahlian_id');

            $tahunAkademik = DB::table('m_periode_magang')->where('id', $lowonganBuka->periode_magang_id)->value('tahun_akademik');
            $tahunAkademik = explode('/', $tahunAkademik)[0];
            $listMahasiswa = DB::table('m_mahasiswa')
                ->select('id', 'user_id')
                ->where('angkatan', $tahunAkademik)
                ->inRandomOrder()
                ->get();
            $kuota = $lowonganBuka->kuota;

            $i = 0;
            foreach ($listMahasiswa as $mahasiswa) {

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
                } elseif (rand(0, 1)) {
                    $pengajuanMagangID = DB::table('t_pengajuan_magang')->insertGetId([
                        'lowongan_magang_id' => $lowonganBuka->id,
                        'mahasiswa_id' => $mahasiswa->id,
                        'status' => 'ditolak',
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
