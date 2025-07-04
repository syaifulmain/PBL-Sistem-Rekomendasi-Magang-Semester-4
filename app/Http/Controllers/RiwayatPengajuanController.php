<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class RiwayatPengajuanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PengajuanMagangModel::with(['mahasiswa', 'lowongan.perusahaan']);

            return DataTables::of($data)
                ->addColumn('mahasiswa', fn($row) => $row->mahasiswa->nama ?? '-')
                ->addColumn('nim', fn($row) => $row->mahasiswa->nim ?? '-')
                ->addColumn('judul_lowongan', fn($row) => $row->lowongan->judul)
                ->addColumn('perusahaan', fn($row) => $row->lowongan->perusahaan->nama ?? '-')
                ->addColumn('action', function ($row) {
                    $detailUrl = route('admin.riwayat-pengajuan.show', $row->id);
                    return view('components.action-buttons', compact('detailUrl'))->render();
                })
                ->editColumn('tanggal_pengajuan', function ($row) {
                    return Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d F Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Riwayat Pengajuan';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];

        return view('admin.riwayat_pengajuan.index', compact('title', 'breadcrumb'));
    }

    public function show($id)
    {
        $data = PengajuanMagangModel::with(['mahasiswa', 'lowongan.perusahaan', 'dokumen.jenisDokumen'])->findOrFail($id);

        $title = 'Detail Riwayat Pengajuan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];
        
        return view('admin.riwayat_pengajuan.detail', compact('data', 'title', 'breadcrumb'));
    }

}
