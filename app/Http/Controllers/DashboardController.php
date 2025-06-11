<?php

namespace App\Http\Controllers;

use App\Helpers\RoleHelper;
use App\Helpers\StatusHelper;
use Illuminate\Http\Request;

use App\Models\LowonganMagangModel;
use App\Models\PengajuanMagangModel;
use App\Models\MagangModel;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        
        if (RoleHelper::is('ADMIN')) {
            return $this->adminDashboard();
        } elseif (RoleHelper::is('DOSEN')) {
            return $this->dosenDashboard();
        } elseif (RoleHelper::is('MAHASISWA')) {
            return $this->mahasiswaDashboard();
        }
        
        return redirect()->route('index');
    }

    public function adminDashboard()
    {
        $title = 'Dashboard Admin';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];

        // 1. Total mahasiswa magang
        $totalMagang = DB::table('t_magang')->count();

        // 2. Data untuk chart tren peminatan
        $trenPeminatan = DB::table('t_pengajuan_magang')
            ->join('t_lowongan_magang', 't_pengajuan_magang.lowongan_magang_id', '=', 't_lowongan_magang.id')
            ->select('t_lowongan_magang.judul', DB::raw('COUNT(t_pengajuan_magang.mahasiswa_id) as total'))
            ->groupBy('t_lowongan_magang.judul')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 3. Data untuk chart status pengajuan
        $statusPengajuan = DB::table('t_pengajuan_magang')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // 4. Data untuk chart mahasiswa per angkatan
        $mahasiswaPerAngkatan = DB::table('t_magang')
            ->join('t_pengajuan_magang', 't_magang.pengajuan_magang_id', '=', 't_pengajuan_magang.id')
            ->join('m_mahasiswa', 't_pengajuan_magang.mahasiswa_id', '=', 'm_mahasiswa.id')
            ->select(DB::raw('m_mahasiswa.angkatan'), DB::raw('COUNT(*) as total'))
            ->groupBy('angkatan')
            ->orderBy('angkatan')
            ->get();

        // 5. Data untuk chart mahasiswa per perusahaan
        $mahasiswaPerPerusahaan = DB::table('t_magang')
            ->join('t_pengajuan_magang', 't_magang.pengajuan_magang_id', '=', 't_pengajuan_magang.id')
            ->join('t_lowongan_magang', 't_pengajuan_magang.lowongan_magang_id', '=', 't_lowongan_magang.id')
            ->leftJoin('m_perusahaan', 't_lowongan_magang.perusahaan_id', '=', 'm_perusahaan.id')
            ->select('m_perusahaan.nama as perusahaan', DB::raw('COUNT(*) as total'))
            ->groupBy('m_perusahaan.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 6. Data untuk chart rasio dosen:mahasiswa
        $totalDosen = DB::table('m_dosen')->count();
        $totalPeserta = DB::table('t_magang')->count();
        $rasioDosen = $totalDosen > 0 ? round($totalPeserta / $totalDosen, 2) : 0;
        
        // Data untuk pie chart rasio
        $rasioData = [
            ['label' => 'Dosen', 'count' => $totalDosen, 'color' => '#4e73df'],
            ['label' => 'Mahasiswa', 'count' => $totalPeserta, 'color' => '#1cc88a']
        ];

        // 8. Data untuk chart efektivitas rekomendasi
        $totalPengajuan = DB::table('t_pengajuan_magang')->whereNot('status', 'batal')->count();
        $pengajuanDisetujui = DB::table('t_pengajuan_magang')->where('status', 'disetujui')->count();
        $pengajuanTidakDisetujui = $totalPengajuan - $pengajuanDisetujui;
        $efektivitasRekomendasi = $totalPengajuan > 0 ? round($pengajuanDisetujui / $totalPengajuan * 100, 2) : 0;

        // Siapkan data untuk chart
        $chartData = [
            'trenPeminatan' => [
                'labels' => $trenPeminatan->pluck('judul'),
                'data' => $trenPeminatan->pluck('total')
            ],
            'statusPengajuan' => [
                'labels' => $statusPengajuan->pluck('status'),
                'data' => $statusPengajuan->pluck('total'),
                'colors' => [
                    'disetujui' => '#57B657',
                    'diajukan' => '#FFC100',
                    'ditolak' => '#FF4747',
                    'batal' => '#282f3a'
                ]
            ],
            'mahasiswaPerAngkatan' => [
                'labels' => $mahasiswaPerAngkatan->pluck('angkatan'),
                'data' => $mahasiswaPerAngkatan->pluck('total')
            ],
            'mahasiswaPerPerusahaan' => [
                'labels' => $mahasiswaPerPerusahaan->pluck('perusahaan')->map(fn($p) => $p ?? 'Tidak Diketahui'),
                'data' => $mahasiswaPerPerusahaan->pluck('total')
            ],
            'rasioDosenMahasiswa' => [
                'labels' => collect($rasioData)->pluck('label'),
                'data' => collect($rasioData)->pluck('count'),
                'colors' => collect($rasioData)->pluck('color')
            ],
            'efektivitas' => [
                'labels' => ['Disetujui', 'Tidak Disetujui'],
                'data' => [$pengajuanDisetujui, $pengajuanTidakDisetujui],
                'colors' => ['#57B657', '#FF4747']
            ]
        ];

        return view('dashboard.admin', compact(
            'title',
            'breadcrumb',
            'totalMagang',
            'totalDosen',
            'rasioDosen',
            'efektivitasRekomendasi',
            'chartData',
            'totalPengajuan',
            'pengajuanDisetujui',
            'pengajuanTidakDisetujui'
        ));
    }

    private function dosenDashboard()
    {
        $title = 'Dashboard Dosen';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];
        
        // Get statistics for dosen
        $stats = [
            'total_magang' => MagangModel::where('dosen_id', auth()->user()->dosen->id)->count(),
            'mahasiswa_aktif' => MagangModel::where('dosen_id', auth()->user()->dosen->id)
                ->where('status', 'aktif')
                ->count()
        ];
        
        return view('dashboard.dosen', compact('title', 'breadcrumb', 'stats'));
    }

    private function mahasiswaDashboard()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        $title = 'Dashboard Mahasiswa';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];

        $pengajuanQuery = PengajuanMagangModel::where('mahasiswa_id', $mahasiswa->id);

        $stats = [
            'lowongan_terdaftar' => $pengajuanQuery->count(),
            'pengajuan_ditolak' => (clone $pengajuanQuery)->whereIn('status', ['ditolak', 'batal'])->count(),
            'pengajuan_aktif' => (clone $pengajuanQuery)->where('status', 'disetujui')->count(),
            'pengajuan_diajukan' => (clone $pengajuanQuery)->where('status', 'diajukan')->count(),
        ];

        return view('dashboard.mahasiswa', compact('title', 'breadcrumb', 'stats'));
    }
}
