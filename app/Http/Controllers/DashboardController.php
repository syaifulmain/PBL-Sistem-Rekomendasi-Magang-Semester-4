<?php

namespace App\Http\Controllers;

use App\Helpers\RoleHelper;
use App\Helpers\StatusHelper;
use Illuminate\Http\Request;

use App\Models\LowonganMagangModel;
use App\Models\PengajuanMagangModel;
use App\Models\MagangModel;
use App\Models\MahasiswaModel;

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

    private function adminDashboard()
    {
        $title = 'Dashboard Admin';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];
        
        // Get statistics
        $stats = [
            'total_lowongan' => LowonganMagangModel::where('status', 'buka')->count(),
            'total_pengajuan_diproses' => PengajuanMagangModel::whereIn('status', ['disetujui', 'ditolak'])->count(),
            'pengajuan_pending' => PengajuanMagangModel::where('status', 'diajukan')->count(),
            'total_magang' => MagangModel::count(),
            'mahasiswa_aktif' => MahasiswaModel::whereHas('magang', function($query) {
                $query->where('t_magang.status', 'aktif');
            })->count()
        ];
        
        return view('dashboard.admin', compact('title', 'breadcrumb', 'stats'));
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
