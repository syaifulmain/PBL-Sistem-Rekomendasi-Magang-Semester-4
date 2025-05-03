<?php

namespace Database\Seeders;

class SeederCounts
{
    // MASTERS
    public const ADMIN = 1;
    public const DOSEN = 5;
    public const MAHASISWA = 90;
    public const PERUSAHAAN = 5;
    public const PROVINSI = [ // kosong akan random semua provinsi
        35, // JAWA TIMUR
        32, // JAWA BARAT
        33, // JAWA TENGAH
    ];
    public const LIST_DOKUMEN_WAJIB = [
            'Pakta Integritas',
            'Daftar Riwayat Hidup',
            'KHS/cetak Siakad',
            'KTP',
            'KTM',
            'Surat Izin Orang Tua'
        ];
    public const LIST_DOKUMEN_LAINNYA = [
            'Kartu BPJS/Asuransi lainnya',
            'SKTM/KIP Kuliah',
            'Proposal Magang',
        ];
    public const PERIODE_MAGANG = 6; // jumlah lowongan PERUSAHAAN * PERIODE_MAGANG
    public const KEAHLIAN_LOWONGAN_MAGANG = 3;
    public const MINAT_USER = 3;
    public const PREFRENSI_LOKASI_USER = 3;
}
