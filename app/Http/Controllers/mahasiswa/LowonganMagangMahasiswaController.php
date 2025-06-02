<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LowonganMagangModel;
use App\Models\PerusahaanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class LowonganMagangMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();

            $mahasiswa = [
                'id' => $user->mahasiswa->id,
                'nama' => $user->mahasiswa->nama,
                'nim' => $user->mahasiswa->nim,
                'ipk' => $user->mahasiswa->ipk,
                'minat' => $user->mahasiswa->getAllMinat(),
                'keahlian' => $user->mahasiswa->getAllKeahlian(),
                'lokasi_preferensi' => $user->mahasiswa->getAllCorPreferensiLokasi(),
            ];

            $listPerusahaan = LowonganMagangModel::where('status', 'buka')
                ->where('minimal_ipk', '<=', $mahasiswa['ipk'])
                ->get();

            $perusahaan = $listPerusahaan->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->getNamaPerusahaan(),
                    'judul' => $item->judul,
                    'alamat' => $item->perusahaan->alamat,
                    'bidang_yang_dibutuhkan' => $item->getKeahlian(),
                    'keahlian_yang_dibutuhkan' => $item->getKeahlianTeknis(),
                    'min_ipk' => $item->minimal_ipk,
                    'lokasi' => $item->getCorLokasi(),
                ];
            })->toArray();

            $data = $this->getRekomendasiMahasiswa($mahasiswa, $perusahaan);

            return DataTables::of(collect($data))
                ->addColumn('action', function ($row) {
                    return '
                    <div class="clickable-row cursor-pointer" data-id="' . $row['id'] . '" onclick="loadLowonganDetail(' . $row['id'] . ')">
                        <h6 class="card-title mb-2 text-primary">' . $row['judul'] . '</h6>
                        <span class="mb-2">' . $row['nama_perusahaan'] . '</span>
                        <p class="card-text mb-1">
                            <small class="text-muted">' . ($row['nama_lokasi'] ?? '-') . '</small>
                        </p>
                    </div>
                ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Lowongan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];

        return view('mahasiswa.lowongan_magang.index', compact('title', 'breadcrumb'));
    }

    private function minatRendah($x)
    {
        if ($x <= 0) return 1;
        if ($x >= 3) return 0;
        return (3 - $x) / 3; // Segitiga [0, 0, 3]
    }

    private function minatTinggi($x)
    {
        if ($x <= 2) return 0;
        if ($x >= 5) return 1;
        return ($x - 2) / 3; // Segitiga [2, 5, 5]
    }

    private function keahlianRendah($x)
    {
        if ($x <= 0) return 1;
        if ($x <= 5) return 1;
        if ($x >= 8) return 0;
        return (8 - $x) / 3; // Trapesium [0, 0, 5, 8]
    }

    private function keahlianTinggi($x)
    {
        if ($x <= 6) return 0;
        if ($x <= 10) return ($x - 6) / 4;
        if ($x >= 15) return 1;
        return 1; // Trapesium [6, 10, 15, 15]
    }

    private function jarakDekat($x)
    {
        if ($x <= 0) return 1;
        if ($x <= 250) return 1;
        if ($x >= 500) return 0;
        return (500 - $x) / 250; // Segitiga [0, 250, 500]
    }

    private function jarakJauh($x)
    {
        if ($x <= 500) return 0;
        if ($x <= 750) return ($x - 500) / 250;
        if ($x >= 1000) return 1;
        return 1; // Segitiga [500, 750, 1000]
    }

    private function selisihIPKKecil($x)
    {
        if ($x <= 0) return 1;
        if ($x >= 2) return 0;
        return (2 - $x) / 2; // Segitiga [0, 0, 2]
    }

    private function selisihIPKBesar($x)
    {
        if ($x <= 1) return 0;
        if ($x <= 3) return ($x - 1) / 2;
        if ($x >= 4) return 1;
        return 1; // Segitiga [1, 3, 4]
    }

    /**
     * Fungsi defuzzifikasi untuk output (Rekomendasi)
     * Menggunakan fungsi linear untuk Tsukamoto
     */
    private function defuzzifikasiRekomendasi($alpha, $kondisi)
    {
        // Fungsi output berbentuk linear
        // Rendah: 0-50, Tinggi: 50-100
        if ($kondisi == 'rendah') {
            return 50 - ($alpha * 50); // Linear turun dari 50 ke 0
        } else {
            return 50 + ($alpha * 50); // Linear naik dari 50 ke 100
        }
    }

    private function hitungJarakKoordinat($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371; // dalam kilometer
        $dlat = deg2rad($lat2 - $lat1);
        $dlon = deg2rad($lon2 - $lon1);

        $a = sin($dlat / 2) * sin($dlat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth_radius * $c;
    }

    private function hitungJarakTerdekat($lokasi_preferensi_array, $lokasi_perusahaan)
    {
        $jarak_minimum = PHP_FLOAT_MAX;
        $lokasi_terdekat = null;

        foreach ($lokasi_preferensi_array as $lokasi_preferensi) {
            $jarak = $this->hitungJarakKoordinat(
                $lokasi_preferensi['latitude'],
                $lokasi_preferensi['longitude'],
                $lokasi_perusahaan['latitude'],
                $lokasi_perusahaan['longitude']
            );

            if ($jarak < $jarak_minimum) {
                $jarak_minimum = $jarak;
                $lokasi_terdekat = $lokasi_preferensi;
            }
        }

        return [
            'jarak' => $jarak_minimum,
            'lokasi_terdekat' => $lokasi_terdekat
        ];
    }

    public function hitungNilaiCrisp($mahasiswa, $perusahaan)
    {
        $minat = $this->hitungKesesuaianMinat($mahasiswa['minat'], $perusahaan['bidang_yang_dibutuhkan']);
        $keahlian = $this->hitungTotalKeahlian($mahasiswa['keahlian'], $perusahaan['keahlian_yang_dibutuhkan']);
        $hasil_jarak = $this->hitungJarakTerdekat($mahasiswa['lokasi_preferensi'], $perusahaan['lokasi']);
        $selisih_ipk = $mahasiswa['ipk'] - $perusahaan['min_ipk'];

        return [
            'minat' => min($minat, 5),
            'keahlian' => $keahlian,
            'jarak' => min($hasil_jarak['jarak'], 10000),
            'selisih_ipk' => min($selisih_ipk, 4),
            'lokasi_terdekat' => $hasil_jarak['lokasi_terdekat']
        ];
    }

    public function prosesFuzzyTsukamoto($mahasiswa, $perusahaan)
    {
        // Fuzzifikasi - Hitung nilai crisp
        $nilai_crisp = $this->hitungNilaiCrisp($mahasiswa, $perusahaan);

        // Hitung derajat keanggotaan
        $mu_minat_rendah = $this->minatRendah($nilai_crisp['minat']);
        $mu_minat_tinggi = $this->minatTinggi($nilai_crisp['minat']);

        $mu_keahlian_rendah = $this->keahlianRendah($nilai_crisp['keahlian']);
        $mu_keahlian_tinggi = $this->keahlianTinggi($nilai_crisp['keahlian']);

        $mu_jarak_dekat = $this->jarakDekat($nilai_crisp['jarak']);
        $mu_jarak_jauh = $this->jarakJauh($nilai_crisp['jarak']);

        $mu_ipk_kecil = $this->selisihIPKKecil($nilai_crisp['selisih_ipk']);
        $mu_ipk_besar = $this->selisihIPKBesar($nilai_crisp['selisih_ipk']);

        // Evaluasi aturan fuzzy (16 rules)
        $rules = [
            // R1: Minat Rendah, Keahlian Rendah, Jarak Dekat, IPK Kecil -> Rendah
            ['alpha' => min($mu_minat_rendah, $mu_keahlian_rendah, $mu_jarak_dekat, $mu_ipk_kecil), 'output' => 'rendah'],

            // R2: Minat Rendah, Keahlian Rendah, Jarak Dekat, IPK Besar -> Rendah
            ['alpha' => min($mu_minat_rendah, $mu_keahlian_rendah, $mu_jarak_dekat, $mu_ipk_besar), 'output' => 'rendah'],

            // R3: Minat Rendah, Keahlian Rendah, Jarak Jauh, IPK Kecil -> Rendah
            ['alpha' => min($mu_minat_rendah, $mu_keahlian_rendah, $mu_jarak_jauh, $mu_ipk_kecil), 'output' => 'rendah'],

            // R4: Minat Rendah, Keahlian Rendah, Jarak Jauh, IPK Besar -> Rendah
            ['alpha' => min($mu_minat_rendah, $mu_keahlian_rendah, $mu_jarak_jauh, $mu_ipk_besar), 'output' => 'rendah'],

            // R5: Minat Rendah, Keahlian Tinggi, Jarak Dekat, IPK Kecil -> Rendah
            ['alpha' => min($mu_minat_rendah, $mu_keahlian_tinggi, $mu_jarak_dekat, $mu_ipk_kecil), 'output' => 'rendah'],

            // R6: Minat Rendah, Keahlian Tinggi, Jarak Dekat, IPK Besar -> Rendah
            ['alpha' => min($mu_minat_rendah, $mu_keahlian_tinggi, $mu_jarak_dekat, $mu_ipk_besar), 'output' => 'rendah'],

            // R7: Minat Rendah, Keahlian Tinggi, Jarak Jauh, IPK Kecil -> Rendah
            ['alpha' => min($mu_minat_rendah, $mu_keahlian_tinggi, $mu_jarak_jauh, $mu_ipk_kecil), 'output' => 'rendah'],

            // R8: Minat Rendah, Keahlian Tinggi, Jarak Jauh, IPK Besar -> Rendah
            ['alpha' => min($mu_minat_rendah, $mu_keahlian_tinggi, $mu_jarak_jauh, $mu_ipk_besar), 'output' => 'rendah'],

            // R9: Minat Tinggi, Keahlian Rendah, Jarak Dekat, IPK Kecil -> Tinggi
            ['alpha' => min($mu_minat_tinggi, $mu_keahlian_rendah, $mu_jarak_dekat, $mu_ipk_kecil), 'output' => 'tinggi'],

            // R10: Minat Tinggi, Keahlian Rendah, Jarak Dekat, IPK Besar -> Rendah
            ['alpha' => min($mu_minat_tinggi, $mu_keahlian_rendah, $mu_jarak_dekat, $mu_ipk_besar), 'output' => 'rendah'],

            // R11: Minat Tinggi, Keahlian Rendah, Jarak Jauh, IPK Kecil -> Rendah
            ['alpha' => min($mu_minat_tinggi, $mu_keahlian_rendah, $mu_jarak_jauh, $mu_ipk_kecil), 'output' => 'rendah'],

            // R12: Minat Tinggi, Keahlian Rendah, Jarak Jauh, IPK Besar -> Rendah
            ['alpha' => min($mu_minat_tinggi, $mu_keahlian_rendah, $mu_jarak_jauh, $mu_ipk_besar), 'output' => 'rendah'],

            // R13: Minat Tinggi, Keahlian Tinggi, Jarak Dekat, IPK Kecil -> Tinggi
            ['alpha' => min($mu_minat_tinggi, $mu_keahlian_tinggi, $mu_jarak_dekat, $mu_ipk_kecil), 'output' => 'tinggi'],

            // R14: Minat Tinggi, Keahlian Tinggi, Jarak Dekat, IPK Besar -> Tinggi
            ['alpha' => min($mu_minat_tinggi, $mu_keahlian_tinggi, $mu_jarak_dekat, $mu_ipk_besar), 'output' => 'tinggi'],

            // R15: Minat Tinggi, Keahlian Tinggi, Jarak Jauh, IPK Kecil -> Tinggi
            ['alpha' => min($mu_minat_tinggi, $mu_keahlian_tinggi, $mu_jarak_jauh, $mu_ipk_kecil), 'output' => 'tinggi'],

            // R16: Minat Tinggi, Keahlian Tinggi, Jarak Jauh, IPK Besar -> Rendah
            ['alpha' => min($mu_minat_tinggi, $mu_keahlian_tinggi, $mu_jarak_jauh, $mu_ipk_besar), 'output' => 'rendah']
        ];

        // Defuzzifikasi menggunakan metode Tsukamoto
        $z_total = 0;
        $alpha_total = 0;
        $detail_rules = [];

        foreach ($rules as $i => $rule) {
            if ($rule['alpha'] > 0) {
                $z = $this->defuzzifikasiRekomendasi($rule['alpha'], $rule['output']);
                $z_total += $rule['alpha'] * $z;
                $alpha_total += $rule['alpha'];

//                $detail_rules[] = [
//                    'rule' => $i + 1,
//                    'alpha' => round($rule['alpha'], 4),
//                    'output' => $rule['output'],
//                    'z' => round($z, 2)
//                ];
            }
        }

        // Hitung skor akhir
        $skor_akhir = $alpha_total > 0 ? $z_total / $alpha_total : 0;

        return [
            'skor_akhir' => round($skor_akhir, 2),
            'nilai_crisp' => $nilai_crisp,
//            'derajat_keanggotaan' => [
//                'minat' => ['rendah' => round($mu_minat_rendah, 4), 'tinggi' => round($mu_minat_tinggi, 4)],
//                'keahlian' => ['rendah' => round($mu_keahlian_rendah, 4), 'tinggi' => round($mu_keahlian_tinggi, 4)],
//                'jarak' => ['dekat' => round($mu_jarak_dekat, 4), 'jauh' => round($mu_jarak_jauh, 4)],
//                'selisih_ipk' => ['kecil' => round($mu_ipk_kecil, 4), 'besar' => round($mu_ipk_besar, 4)]
//            ],
//            'rules_fired' => $detail_rules,
//            'alpha_total' => round($alpha_total, 4),
//            'z_total' => round($z_total, 2)
        ];
    }

    private function hitungKesesuaianMinat($minat_mahasiswa, $bidang_perusahaan)
    {
        $cocok = 0;
        foreach ($minat_mahasiswa as $minat) {
            if (in_array($minat, $bidang_perusahaan)) {
                $cocok++;
            }
        }
        return $cocok;
    }

    private function hitungTotalKeahlian($keahlian_mahasiswa, $keahlian_perusahaan)
    {
        $total = 0;

        foreach ($keahlian_perusahaan as $keahlian_dibutuhkan => $level_dibutuhkan) {
            if (isset($keahlian_mahasiswa[$keahlian_dibutuhkan])) {

                $nilai_mahasiswa = $keahlian_mahasiswa[$keahlian_dibutuhkan];
                $nilai_dibutuhkan = $level_dibutuhkan;

                if ($nilai_mahasiswa >= $nilai_dibutuhkan) {
                    $total += $nilai_mahasiswa;
                } else {
                    $total += $nilai_mahasiswa * 0.5; // 50% dari kemampuan mahasiswa
                }
            }
        }

        return $total;
    }

    public function getRekomendasiMahasiswa($mahasiswa, $daftar_perusahaan)
    {
        $hasil = [];

        foreach ($daftar_perusahaan as $perusahaan) {
            $fuzzy_result = $this->prosesFuzzyTsukamoto($mahasiswa, $perusahaan);
            $hasil[] = [
                'id' => $perusahaan['id'],
                'nama_perusahaan' => $perusahaan['nama'],
                'judul' => $perusahaan['judul'],
                'nama_lokasi' => $perusahaan['alamat'],
                'skor' => $fuzzy_result['skor_akhir'],
                'detail' => $fuzzy_result['nilai_crisp'],
            ];
        }

        // Urutkan berdasarkan skor tertinggi
        usort($hasil, function ($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        return $hasil;
    }

    public function show($id)
    {
        $data = LowonganMagangModel::with(['perusahaan', 'periodeMagang', 'keahlian', 'dokumen'])->findOrFail($id);

        return view('mahasiswa.lowongan_magang.detail', compact('data'));
    }
}
