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

            // Gunakan kedua metode sekaligus
            $data = $this->getRekomendasiGabungan($mahasiswa, $perusahaan);

            return DataTables::of(collect($data))
                ->addColumn('action', function ($row) {
                    return '
                    <div class="clickable-row cursor-pointer" data-id="' . $row['id'] . '" onclick="loadLowonganDetail(' . $row['id'] . ')">
                        <h6 class="card-title mb-2 text-primary">' . $row['judul'] . '</h6>
                        <span class="mb-2">' . $row['nama_perusahaan'] . '</span>
                        <p class="card-text mb-1">
                            <small class="text-muted">' . ($row['nama_lokasi'] ?? '-') . '</small>
                        </p>
                        <div class="row">
                            <div class="col-6">
                                <p class="card-text mb-1">
                                    <small class="text-info">Fuzzy: ' . $row['skor_fuzzy'] . '</small>
                                </p>
                            </div>
                            <div class="col-6">
                                <p class="card-text mb-1">
                                    <small class="text-warning">WSM: ' . $row['skor_wsm'] . '</small>
                                </p>
                            </div>
                        </div>
                        <p class="card-text mb-1">
                            <small class="text-success"><strong>Skor Gabungan: ' . $row['skor_gabungan'] . '</strong></small>
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

    // ========== METODE GABUNGAN (FUZZY + WSM) ==========

    /**
     * Konfigurasi bobot untuk metode gabungan
     */
    private function getBobotGabungan()
    {
        return [
            'fuzzy' => 0.4,     // 60% bobot untuk Fuzzy Tsukamoto
            'wsm' => 0.5        // 40% bobot untuk WSM
        ];
    }

    /**
     * Metode untuk menggabungkan hasil Fuzzy Tsukamoto dan WSM
     */
    public function getRekomendasiGabungan($mahasiswa, $daftar_perusahaan)
    {
        // Hitung hasil dengan kedua metode
        $hasil_fuzzy = $this->getRekomendasiMahasiswa($mahasiswa, $daftar_perusahaan);
        $hasil_wsm = $this->getRekomendasiMahasiswaWSM($mahasiswa, $daftar_perusahaan);

        // Ambil bobot gabungan
        $bobot = $this->getBobotGabungan();

        $hasil_gabungan = [];

        // Gabungkan hasil berdasarkan ID perusahaan
        foreach ($hasil_fuzzy as $fuzzy) {
            // Cari hasil WSM yang sesuai
            $wsm_match = array_filter($hasil_wsm, function($wsm) use ($fuzzy) {
                return $wsm['id'] == $fuzzy['id'];
            });

            if (!empty($wsm_match)) {
                $wsm = array_values($wsm_match)[0];

                // Hitung skor gabungan dengan weighted average
                $skor_gabungan = ($fuzzy['skor'] * $bobot['fuzzy']) + ($wsm['skor'] * $bobot['wsm']);

                $hasil_gabungan[] = [
                    'id' => $fuzzy['id'],
                    'nama_perusahaan' => $fuzzy['nama_perusahaan'],
                    'judul' => $fuzzy['judul'],
                    'nama_lokasi' => $fuzzy['nama_lokasi'],
                    'skor_fuzzy' => $fuzzy['skor'],
                    'skor_wsm' => $wsm['skor'],
                    'skor_gabungan' => round($skor_gabungan, 2),
                    'detail_fuzzy' => $fuzzy['detail'],
                    'detail_wsm' => $wsm['detail'],
                    'metode' => 'Gabungan (Fuzzy + WSM)',
                    'bobot_fuzzy' => $bobot['fuzzy'],
                    'bobot_wsm' => $bobot['wsm']
                ];
            }
        }

        // Urutkan berdasarkan skor gabungan tertinggi
        usort($hasil_gabungan, function ($a, $b) {
            return $b['skor_gabungan'] <=> $a['skor_gabungan'];
        });

        return $hasil_gabungan;
    }

    /**
     * Metode alternatif: Rata-rata ranking (Borda Count)
     */
    public function getRekomendasiGabunganBorda($mahasiswa, $daftar_perusahaan)
    {
        // Hitung hasil dengan kedua metode
        $hasil_fuzzy = $this->getRekomendasiMahasiswa($mahasiswa, $daftar_perusahaan);
        $hasil_wsm = $this->getRekomendasiMahasiswaWSM($mahasiswa, $daftar_perusahaan);

        $hasil_gabungan = [];
        $total_alternatif = count($hasil_fuzzy);

        // Gabungkan hasil berdasarkan ID perusahaan
        foreach ($hasil_fuzzy as $rank_fuzzy => $fuzzy) {
            // Cari ranking WSM yang sesuai
            $rank_wsm = 0;
            foreach ($hasil_wsm as $index => $wsm) {
                if ($wsm['id'] == $fuzzy['id']) {
                    $rank_wsm = $index;
                    break;
                }
            }

            // Hitung skor Borda (total_alternatif - ranking)
            $skor_borda_fuzzy = $total_alternatif - $rank_fuzzy;
            $skor_borda_wsm = $total_alternatif - $rank_wsm;
            $skor_borda_gabungan = $skor_borda_fuzzy + $skor_borda_wsm;

            $hasil_gabungan[] = [
                'id' => $fuzzy['id'],
                'nama_perusahaan' => $fuzzy['nama_perusahaan'],
                'judul' => $fuzzy['judul'],
                'nama_lokasi' => $fuzzy['nama_lokasi'],
                'skor_fuzzy' => $fuzzy['skor'],
                'skor_wsm' => $hasil_wsm[$rank_wsm]['skor'],
                'rank_fuzzy' => $rank_fuzzy + 1,
                'rank_wsm' => $rank_wsm + 1,
                'skor_borda' => $skor_borda_gabungan,
                'skor_gabungan' => round(($skor_borda_gabungan / ($total_alternatif * 2)) * 100, 2),
                'metode' => 'Gabungan (Borda Count)'
            ];
        }

        // Urutkan berdasarkan skor Borda tertinggi
        usort($hasil_gabungan, function ($a, $b) {
            return $b['skor_borda'] <=> $a['skor_borda'];
        });

        return $hasil_gabungan;
    }

    /**
     * Metode alternatif: Normalisasi dan rata-rata
     */
    public function getRekomendasiGabunganNormalisasi($mahasiswa, $daftar_perusahaan)
    {
        // Hitung hasil dengan kedua metode
        $hasil_fuzzy = $this->getRekomendasiMahasiswa($mahasiswa, $daftar_perusahaan);
        $hasil_wsm = $this->getRekomendasiMahasiswaWSM($mahasiswa, $daftar_perusahaan);

        // Normalisasi skor ke rentang 0-1
        $skor_fuzzy = array_column($hasil_fuzzy, 'skor');
        $skor_wsm = array_column($hasil_wsm, 'skor');

        $min_fuzzy = min($skor_fuzzy);
        $max_fuzzy = max($skor_fuzzy);
        $min_wsm = min($skor_wsm);
        $max_wsm = max($skor_wsm);

        $hasil_gabungan = [];

        foreach ($hasil_fuzzy as $fuzzy) {
            // Cari hasil WSM yang sesuai
            $wsm_match = array_filter($hasil_wsm, function($wsm) use ($fuzzy) {
                return $wsm['id'] == $fuzzy['id'];
            });

            if (!empty($wsm_match)) {
                $wsm = array_values($wsm_match)[0];

                // Normalisasi skor
                $fuzzy_norm = ($max_fuzzy == $min_fuzzy) ? 1 : ($fuzzy['skor'] - $min_fuzzy) / ($max_fuzzy - $min_fuzzy);
                $wsm_norm = ($max_wsm == $min_wsm) ? 1 : ($wsm['skor'] - $min_wsm) / ($max_wsm - $min_wsm);

                // Rata-rata skor ternormalisasi
                $skor_gabungan = (($fuzzy_norm + $wsm_norm) / 2) * 100;

                $hasil_gabungan[] = [
                    'id' => $fuzzy['id'],
                    'nama_perusahaan' => $fuzzy['nama_perusahaan'],
                    'judul' => $fuzzy['judul'],
                    'nama_lokasi' => $fuzzy['nama_lokasi'],
                    'skor_fuzzy' => $fuzzy['skor'],
                    'skor_wsm' => $wsm['skor'],
                    'skor_fuzzy_norm' => round($fuzzy_norm, 4),
                    'skor_wsm_norm' => round($wsm_norm, 4),
                    'skor_gabungan' => round($skor_gabungan, 2),
                    'metode' => 'Gabungan (Normalisasi)'
                ];
            }
        }

        // Urutkan berdasarkan skor gabungan tertinggi
        usort($hasil_gabungan, function ($a, $b) {
            return $b['skor_gabungan'] <=> $a['skor_gabungan'];
        });

        return $hasil_gabungan;
    }

    /**
     * Konfigurasi bobot untuk WSM
     * Total bobot harus = 1
     */
    private function getBobotWSM()
    {
        return [
            'minat' => 0.30,        // 30% - Kesesuaian minat
            'keahlian' => 0.35,     // 35% - Kesesuaian keahlian
            'jarak' => 0.20,        // 20% - Kedekatan lokasi
            'selisih_ipk' => 0.15   // 15% - Selisih IPK
        ];
    }

    /**
     * Normalisasi nilai untuk WSM
     * Menggunakan normalisasi min-max ke rentang 0-1
     */
    private function normalisiNilaiWSM($nilai_raw, $min_val, $max_val, $is_benefit = true)
    {
        if ($max_val == $min_val) {
            return 1; // Jika semua nilai sama, beri nilai maksimal
        }

        if ($is_benefit) {
            // Untuk kriteria benefit (semakin besar semakin baik)
            return ($nilai_raw - $min_val) / ($max_val - $min_val);
        } else {
            // Untuk kriteria cost (semakin kecil semakin baik)
            return ($max_val - $nilai_raw) / ($max_val - $min_val);
        }
    }

    /**
     * Proses WSM untuk satu perusahaan
     */
    public function prosesWSM($mahasiswa, $perusahaan, $nilai_min_max)
    {
        // Hitung nilai crisp
        $nilai_crisp = $this->hitungNilaiCrisp($mahasiswa, $perusahaan);

        // Normalisasi setiap kriteria
        $minat_norm = $this->normalisiNilaiWSM(
            $nilai_crisp['minat'],
            $nilai_min_max['minat']['min'],
            $nilai_min_max['minat']['max'],
            true  // benefit
        );

        $keahlian_norm = $this->normalisiNilaiWSM(
            $nilai_crisp['keahlian'],
            $nilai_min_max['keahlian']['min'],
            $nilai_min_max['keahlian']['max'],
            true  // benefit
        );

        $jarak_norm = $this->normalisiNilaiWSM(
            $nilai_crisp['jarak'],
            $nilai_min_max['jarak']['min'],
            $nilai_min_max['jarak']['max'],
            false // cost (semakin dekat semakin baik)
        );

        $ipk_norm = $this->normalisiNilaiWSM(
            $nilai_crisp['selisih_ipk'],
            $nilai_min_max['selisih_ipk']['min'],
            $nilai_min_max['selisih_ipk']['max'],
            true  // benefit (selisih IPK tinggi = bagus)
        );

        // Ambil bobot
        $bobot = $this->getBobotWSM();

        // Hitung skor WSM (weighted sum)
        $skor_wsm = ($minat_norm * $bobot['minat']) +
            ($keahlian_norm * $bobot['keahlian']) +
            ($jarak_norm * $bobot['jarak']) +
            ($ipk_norm * $bobot['selisih_ipk']);

        // Konversi ke skala 0-100 untuk kemudahan interpretasi
        $skor_final = $skor_wsm * 100;

        return [
            'skor_akhir' => round($skor_final, 2),
            'nilai_crisp' => $nilai_crisp,
            'nilai_normalisasi' => [
                'minat' => round($minat_norm, 4),
                'keahlian' => round($keahlian_norm, 4),
                'jarak' => round($jarak_norm, 4),
                'selisih_ipk' => round($ipk_norm, 4)
            ],
            'bobot' => $bobot,
            'skor_wsm' => round($skor_wsm, 4)
        ];
    }

    /**
     * Mendapatkan nilai min dan max untuk normalisasi
     */
    private function hitungMinMaxNilai($mahasiswa, $daftar_perusahaan)
    {
        $nilai_semua = [];

        foreach ($daftar_perusahaan as $perusahaan) {
            $nilai_crisp = $this->hitungNilaiCrisp($mahasiswa, $perusahaan);
            $nilai_semua[] = $nilai_crisp;
        }

        if (empty($nilai_semua)) {
            return [
                'minat' => ['min' => 0, 'max' => 1],
                'keahlian' => ['min' => 0, 'max' => 1],
                'jarak' => ['min' => 0, 'max' => 1],
                'selisih_ipk' => ['min' => 0, 'max' => 1]
            ];
        }

        return [
            'minat' => [
                'min' => min(array_column($nilai_semua, 'minat')),
                'max' => max(array_column($nilai_semua, 'minat'))
            ],
            'keahlian' => [
                'min' => min(array_column($nilai_semua, 'keahlian')),
                'max' => max(array_column($nilai_semua, 'keahlian'))
            ],
            'jarak' => [
                'min' => min(array_column($nilai_semua, 'jarak')),
                'max' => max(array_column($nilai_semua, 'jarak'))
            ],
            'selisih_ipk' => [
                'min' => min(array_column($nilai_semua, 'selisih_ipk')),
                'max' => max(array_column($nilai_semua, 'selisih_ipk'))
            ]
        ];
    }

    /**
     * Mendapatkan rekomendasi menggunakan metode WSM
     */
    public function getRekomendasiMahasiswaWSM($mahasiswa, $daftar_perusahaan)
    {
        // Hitung nilai min-max untuk normalisasi
        $nilai_min_max = $this->hitungMinMaxNilai($mahasiswa, $daftar_perusahaan);

        $hasil = [];

        foreach ($daftar_perusahaan as $perusahaan) {
            $wsm_result = $this->prosesWSM($mahasiswa, $perusahaan, $nilai_min_max);
            $hasil[] = [
                'id' => $perusahaan['id'],
                'nama_perusahaan' => $perusahaan['nama'],
                'judul' => $perusahaan['judul'],
                'nama_lokasi' => $perusahaan['alamat'],
                'skor' => $wsm_result['skor_akhir'],
                'detail' => $wsm_result,
                'metode' => 'WSM'
            ];
        }

        // Urutkan berdasarkan skor tertinggi
        usort($hasil, function ($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        return $hasil;
    }

    // ========== METODE FUZZY TSUKAMOTO (EXISTING) ==========

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
            }
        }

        // Hitung skor akhir
        $skor_akhir = $alpha_total > 0 ? $z_total / $alpha_total : 0;

        return [
            'skor_akhir' => round($skor_akhir, 2),
            'nilai_crisp' => $nilai_crisp,
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
                'metode' => 'Fuzzy Tsukamoto'
            ];
        }

        // Urutkan berdasarkan skor tertinggi
        usort($hasil, function ($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        return $hasil;
    }

    // ========== METODE PERBANDINGAN ==========

    /**
     * Endpoint untuk mendapatkan detail perbandingan metode gabungan
     */
    public function detailGabungan(Request $request)
    {
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

        // Hitung dengan semua metode gabungan
        $hasil_weighted = $this->getRekomendasiGabungan($mahasiswa, $perusahaan);
        $hasil_borda = $this->getRekomendasiGabunganBorda($mahasiswa, $perusahaan);
        $hasil_normalisasi = $this->getRekomendasiGabunganNormalisasi($mahasiswa, $perusahaan);

        return response()->json([
            'weighted_average' => $hasil_weighted,
            'borda_count' => $hasil_borda,
            'normalisasi' => $hasil_normalisasi,
            'summary' => [
                'total_lowongan' => count($hasil_weighted),
                'metode_digunakan' => ['Fuzzy Tsukamoto', 'WSM'],
                'metode_gabungan' => ['Weighted Average', 'Borda Count', 'Normalisasi'],
                'bobot_default' => $this->getBobotGabungan()
            ]
        ]);
    }
    public function perbandinganMetode(Request $request)
    {
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

        // Hitung dengan kedua metode
        $hasil_fuzzy = $this->getRekomendasiMahasiswa($mahasiswa, $perusahaan);
        $hasil_wsm = $this->getRekomendasiMahasiswaWSM($mahasiswa, $perusahaan);

        return response()->json([
            'fuzzy_tsukamoto' => $hasil_fuzzy,
            'wsm' => $hasil_wsm,
            'perbandingan' => $this->bandingkanHasil($hasil_fuzzy, $hasil_wsm)
        ]);
    }

    /**
     * Membandingkan hasil kedua metode
     */
    private function bandingkanHasil($hasil_fuzzy, $hasil_wsm)
    {
        $perbandingan = [];

        foreach ($hasil_fuzzy as $index => $fuzzy) {
            $wsm = $hasil_wsm[$index] ?? null;

            if ($wsm && $fuzzy['id'] == $wsm['id']) {
                $perbandingan[] = [
                    'id' => $fuzzy['id'],
                    'nama_perusahaan' => $fuzzy['nama_perusahaan'],
                    'judul' => $fuzzy['judul'],
                    'skor_fuzzy' => $fuzzy['skor'],
                    'skor_wsm' => $wsm['skor'],
                    'selisih_skor' => abs($fuzzy['skor'] - $wsm['skor']),
                    'rank_fuzzy' => $index + 1,
                    'rank_wsm' => array_search($wsm, $hasil_wsm) + 1
                ];
            }
        }

        return $perbandingan;
    }

    public function show($id)
    {
        $data = LowonganMagangModel::with(['perusahaan', 'periodeMagang', 'keahlian', 'dokumen'])->findOrFail($id);

        return view('mahasiswa.lowongan_magang.detail', compact('data'));
    }
}
