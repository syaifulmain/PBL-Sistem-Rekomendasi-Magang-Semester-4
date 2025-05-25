<?php

class FuzzyTsukamotoSPK {

    /**
     * Fungsi keanggotaan untuk Minat Bidang
     */
    private function minatRendah($x) {
        if ($x <= 0) return 1;
        if ($x >= 3) return 0;
        return (3 - $x) / 3; // Segitiga [0, 0, 3]
    }

    private function minatTinggi($x) {
        if ($x <= 2) return 0;
        if ($x >= 5) return 1;
        return ($x - 2) / 3; // Segitiga [2, 5, 5]
    }

    /**
     * Fungsi keanggotaan untuk Keahlian Teknis
     */
    private function keahlianRendah($x) {
        if ($x <= 0) return 1;
        if ($x <= 5) return 1;
        if ($x >= 8) return 0;
        return (8 - $x) / 3; // Trapesium [0, 0, 5, 8]
    }

    private function keahlianTinggi($x) {
        if ($x <= 6) return 0;
        if ($x <= 10) return ($x - 6) / 4;
        if ($x >= 15) return 1;
        return 1; // Trapesium [6, 10, 15, 15]
    }

    /**
     * Fungsi keanggotaan untuk Jarak
     */
    private function jarakDekat($x) {
        if ($x <= 0) return 1;
        if ($x <= 2500) return 1;
        if ($x >= 5000) return 0;
        return (5000 - $x) / 2500; // Segitiga [0, 2500, 5000]
    }

    private function jarakJauh($x) {
        if ($x <= 5000) return 0;
        if ($x <= 7500) return ($x - 5000) / 2500;
        if ($x >= 10000) return 1;
        return 1; // Segitiga [5000, 7500, 10000]
    }

    /**
     * Fungsi keanggotaan untuk Selisih IPK
     */
    private function selisihIPKKecil($x) {
        if ($x <= 0) return 1;
        if ($x >= 1) return 0;
        return (1 - $x) / 1; // Segitiga [0, 0, 1]
    }

    private function selisihIPKBesar($x) {
        if ($x <= 0.5) return 0;
        if ($x <= 1.25) return ($x - 0.5) / 0.75;
        if ($x >= 2) return 1;
        return 1; // Segitiga [0.5, 2, 2]
    }

    /**
     * Fungsi defuzzifikasi untuk output (Rekomendasi)
     * Menggunakan fungsi linear untuk Tsukamoto
     */
    private function defuzzifikasiRekomendasi($alpha, $kondisi) {
        // Fungsi output berbentuk linear
        // Rendah: 0-50, Tinggi: 50-100
        if ($kondisi == 'rendah') {
            return 50 - ($alpha * 50); // Linear turun dari 50 ke 0
        } else {
            return 50 + ($alpha * 50); // Linear naik dari 50 ke 100
        }
    }

    /**
     * Menghitung jarak antara dua titik koordinat
     */
    private function hitungJarakKoordinat($lat1, $lon1, $lat2, $lon2) {
        $earth_radius = 6371000; // dalam meter
        $dlat = deg2rad($lat2 - $lat1);
        $dlon = deg2rad($lon2 - $lon1);

        $a = sin($dlat/2) * sin($dlat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earth_radius * $c;
    }

    /**
     * Menghitung jarak terdekat dari multiple lokasi preferensi ke lokasi perusahaan
     */
    private function hitungJarakTerdekat($lokasi_preferensi_array, $lokasi_perusahaan) {
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

    /**
     * Menghitung nilai crisp untuk setiap parameter
     */
    public function hitungNilaiCrisp($mahasiswa, $perusahaan) {
        // Hitung minat bidang
        $minat = $this->hitungKesesuaianMinat($mahasiswa['minat'], $perusahaan['bidang_yang_dibutuhkan']);

        // Hitung keahlian teknis
        $keahlian = $this->hitungTotalKeahlian($mahasiswa['keahlian'], $perusahaan['keahlian_yang_dibutuhkan']);

        // Hitung jarak terdekat
        $hasil_jarak = $this->hitungJarakTerdekat($mahasiswa['lokasi_preferensi'], $perusahaan['lokasi']);

        // Hitung selisih IPK
        $selisih_ipk = max(0, $perusahaan['min_ipk'] - $mahasiswa['ipk']);

        return [
            'minat' => min($minat, 5),
            'keahlian' => $keahlian,
            'jarak' => min($hasil_jarak['jarak'], 10000),
            'selisih_ipk' => min($selisih_ipk, 2),
            'lokasi_terdekat' => $hasil_jarak['lokasi_terdekat']
        ];
    }

    /**
     * Proses Fuzzy Tsukamoto lengkap
     */
    public function prosesFuzzyTsukamoto($mahasiswa, $perusahaan) {
        // 1. Fuzzifikasi - Hitung nilai crisp
        $nilai_crisp = $this->hitungNilaiCrisp($mahasiswa, $perusahaan);

        // 2. Hitung derajat keanggotaan
        $mu_minat_rendah = $this->minatRendah($nilai_crisp['minat']);
        $mu_minat_tinggi = $this->minatTinggi($nilai_crisp['minat']);

        $mu_keahlian_rendah = $this->keahlianRendah($nilai_crisp['keahlian']);
        $mu_keahlian_tinggi = $this->keahlianTinggi($nilai_crisp['keahlian']);

        $mu_jarak_dekat = $this->jarakDekat($nilai_crisp['jarak']);
        $mu_jarak_jauh = $this->jarakJauh($nilai_crisp['jarak']);

        $mu_ipk_kecil = $this->selisihIPKKecil($nilai_crisp['selisih_ipk']);
        $mu_ipk_besar = $this->selisihIPKBesar($nilai_crisp['selisih_ipk']);

        // 3. Evaluasi aturan fuzzy (16 rules)
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

        // 4. Defuzzifikasi menggunakan metode Tsukamoto
        $z_total = 0;
        $alpha_total = 0;
        $detail_rules = [];

        foreach ($rules as $i => $rule) {
            if ($rule['alpha'] > 0) {
                $z = $this->defuzzifikasiRekomendasi($rule['alpha'], $rule['output']);
                $z_total += $rule['alpha'] * $z;
                $alpha_total += $rule['alpha'];

                $detail_rules[] = [
                    'rule' => $i + 1,
                    'alpha' => round($rule['alpha'], 4),
                    'output' => $rule['output'],
                    'z' => round($z, 2)
                ];
            }
        }

        // Hitung skor akhir
        $skor_akhir = $alpha_total > 0 ? $z_total / $alpha_total : 0;

        return [
            'skor_akhir' => round($skor_akhir, 2),
            'nilai_crisp' => $nilai_crisp,
            'derajat_keanggotaan' => [
                'minat' => ['rendah' => round($mu_minat_rendah, 4), 'tinggi' => round($mu_minat_tinggi, 4)],
                'keahlian' => ['rendah' => round($mu_keahlian_rendah, 4), 'tinggi' => round($mu_keahlian_tinggi, 4)],
                'jarak' => ['dekat' => round($mu_jarak_dekat, 4), 'jauh' => round($mu_jarak_jauh, 4)],
                'selisih_ipk' => ['kecil' => round($mu_ipk_kecil, 4), 'besar' => round($mu_ipk_besar, 4)]
            ],
            'rules_fired' => $detail_rules,
            'alpha_total' => round($alpha_total, 4),
            'z_total' => round($z_total, 2)
        ];
    }

    /**
     * Helper functions
     */
    private function hitungKesesuaianMinat($minat_mahasiswa, $bidang_perusahaan) {
        $cocok = 0;
        foreach ($minat_mahasiswa as $minat) {
            if (in_array($minat, $bidang_perusahaan)) {
                $cocok++;
            }
        }
        return $cocok;
    }

    private function hitungTotalKeahlian($keahlian_mahasiswa, $keahlian_perusahaan) {
        $total = 0;

        foreach ($keahlian_perusahaan as $keahlian_dibutuhkan => $level_dibutuhkan) {
            if (isset($keahlian_mahasiswa[$keahlian_dibutuhkan])) {
                // Konversi level ke nilai numerik
                $nilai_mahasiswa = $keahlian_mahasiswa[$keahlian_dibutuhkan];
                $nilai_dibutuhkan = $level_dibutuhkan;

                // Hitung skor berdasarkan kesesuaian level
                if ($nilai_mahasiswa >= $nilai_dibutuhkan) {
                    // Jika mahasiswa memenuhi atau melebihi yang dibutuhkan
                    $total += $nilai_mahasiswa;
                } else {
                    // Jika mahasiswa di bawah yang dibutuhkan, beri skor parsial
                    $total += $nilai_mahasiswa * 0.5; // 50% dari kemampuan mahasiswa
                }
            }
        }

        return $total;
    }

    /**
     * Mendapatkan rekomendasi untuk mahasiswa
     */
    public function getRekomendasiMahasiswa($mahasiswa, $daftar_perusahaan) {
        $hasil = [];

        foreach ($daftar_perusahaan as $perusahaan) {
            $fuzzy_result = $this->prosesFuzzyTsukamoto($mahasiswa, $perusahaan);
            $hasil[] = [
                'perusahaan_id' => $perusahaan['id'],
                'nama_perusahaan' => $perusahaan['nama'],
                'skor' => $fuzzy_result['skor_akhir'],
                'detail' => $fuzzy_result
            ];
        }

        // Urutkan berdasarkan skor tertinggi
        usort($hasil, function($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        return $hasil;
    }
}

// ========================================
// CONTOH PENGGUNAAN DENGAN MULTIPLE LOKASI
// ========================================

// Data contoh mahasiswa dengan multiple lokasi preferensi
$mahasiswa = [
    'id' => 1,
    'nama' => 'John Doe',
    'nim' => '12345678',
    'ipk' => 3.5,
    'minat' => ['Web Development', 'Database', 'UI/UX'],
    'keahlian' => [
        'PHP' => 3,        // Level 3
        'JavaScript' => 2, // Level 2
        'MySQL' => 1,    // Level 1
        'Java' => 1      // Level 1
    ],
    'lokasi_preferensi' => [
        // Jakarta Pusat
        [
            'nama' => 'Jakarta Pusat',
            'latitude' => -6.2088,
            'longitude' => 106.8456
        ],
        // Bandung
        [
            'nama' => 'Bandung',
            'latitude' => -6.9175,
            'longitude' => 107.6191
        ],
        // Surabaya
        [
            'nama' => 'Surabaya',
            'latitude' => -7.2575,
            'longitude' => 112.7521
        ]
    ]
];

$perusahaan = [
    [
        'id' => 1,
        'nama' => 'PT Tech Solutions Jakarta',
        'bidang_yang_dibutuhkan' => ['Web Development', 'Database', 'UI/UX'],
        'keahlian_yang_dibutuhkan' => [
            'PHP' => 3,        // Butuh level Ahli
            'MySQL' => 2,  // Butuh level Menengah
            'JavaScript' => 2  // Butuh level Menengah
        ],
        'min_ipk' => 3.0,
        'lokasi' => [
            'latitude' => -6.2000,  // Jakarta Selatan
            'longitude' => 106.8400
        ]
    ],
    [
        'id' => 2,
        'nama' => 'CV Digital Creative Bandung',
        'bidang_yang_dibutuhkan' => ['Mobile App', 'UI/UX'],
        'keahlian_yang_dibutuhkan' => [
            'Java' => 1,     // Butuh level Pemula
            'Kotlin' => 2, // Butuh level Menengah
            'Flutter' => 3     // Butuh level Ahli
        ],
        'min_ipk' => 3.2,
        'lokasi' => [
            'latitude' => -6.9000,  // Bandung
            'longitude' => 107.6000
        ]
    ],
    [
        'id' => 3,
        'nama' => 'PT Innovation Hub Surabaya',
        'bidang_yang_dibutuhkan' => ['Web Development', 'Mobile App'],
        'keahlian_yang_dibutuhkan' => [
            'PHP' => 2,
            'JavaScript' => 3,
            'Java' => 2
        ],
        'min_ipk' => 3.3,
        'lokasi' => [
            'latitude' => -7.2500,  // Surabaya
            'longitude' => 112.7500
        ]
    ]
];

// Inisialisasi Fuzzy Tsukamoto
$fuzzy = new FuzzyTsukamotoSPK();

// Test dengan satu perusahaan
echo "<h2>Detail Proses Fuzzy Tsukamoto dengan Multiple Lokasi</h2>\n";
$hasil = $fuzzy->prosesFuzzyTsukamoto($mahasiswa, $perusahaan[0]);

echo "<h3>Nilai Crisp Input:</h3>\n";
echo "<ul>\n";
echo "<li>Minat: " . $hasil['nilai_crisp']['minat'] . "</li>\n";
echo "<li>Keahlian: " . $hasil['nilai_crisp']['keahlian'] . "</li>\n";
echo "<li>Jarak Terdekat: " . round($hasil['nilai_crisp']['jarak']) . " meter</li>\n";
echo "<li>Lokasi Terdekat: " . $hasil['nilai_crisp']['lokasi_terdekat']['nama'] . "</li>\n";
echo "<li>Selisih IPK: " . $hasil['nilai_crisp']['selisih_ipk'] . "</li>\n";
echo "</ul>\n";

echo "<h3>Derajat Keanggotaan:</h3>\n";
echo "<ul>\n";
foreach ($hasil['derajat_keanggotaan'] as $param => $values) {
    echo "<li><strong>" . ucfirst($param) . ":</strong> ";
    foreach ($values as $fuzzy_set => $value) {
        echo $fuzzy_set . "=" . $value . " ";
    }
    echo "</li>\n";
}
echo "</ul>\n";

echo "<h3>Rules yang Aktif:</h3>\n";
echo "<ul>\n";
foreach ($hasil['rules_fired'] as $rule) {
    echo "<li>Rule " . $rule['rule'] . ": α=" . $rule['alpha'] . ", output=" . $rule['output'] . ", z=" . $rule['z'] . "</li>\n";
}
echo "</ul>\n";

echo "<h3>Hasil Akhir:</h3>\n";
echo "<ul>\n";
echo "<li>Total α: " . $hasil['alpha_total'] . "</li>\n";
echo "<li>Total z×α: " . $hasil['z_total'] . "</li>\n";
echo "<li><strong>Skor Rekomendasi: " . $hasil['skor_akhir'] . "</strong></li>\n";
echo "</ul>\n";

// Test rekomendasi lengkap
echo "<h2>Rekomendasi Perusahaan untuk " . $mahasiswa['nama'] . "</h2>\n";
$rekomendasi = $fuzzy->getRekomendasiMahasiswa($mahasiswa, $perusahaan);

foreach ($rekomendasi as $index => $item) {
    echo "<h3>" . ($index + 1) . ". " . $item['nama_perusahaan'] . " (Skor: " . $item['skor'] . ")</h3>\n";
    echo "<p>Lokasi terdekat mahasiswa: " . $item['detail']['nilai_crisp']['lokasi_terdekat']['nama'] . "</p>\n";
    echo "<p>Jarak: " . round($item['detail']['nilai_crisp']['jarak']) . " meter</p>\n";
}

?>
