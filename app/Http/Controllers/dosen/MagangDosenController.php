<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use App\Models\EvaluasiBimbinganModel;
use App\Models\MagangModel;
use App\Models\PengajuanMagangModel;
use App\Helpers\StatusHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MagangDosenController extends Controller
{
    public function index(Request $request)
    {
        $data = MagangModel::where('dosen_id', auth()->user()->dosen->id)
            ->orderBy('status', 'asc')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addColumn('mahasiswa', function ($row) {
                    return $row->pengajuanMagang->mahasiswa->nama . ' ' .$row->pengajuanMagang->mahasiswa->nim;
                })
                ->addColumn('lowongan', function ($row) {
                    return $row->pengajuanMagang->lowongan->judul;
                })
                ->addColumn('perusahaan', function ($row) {
                    return $row->pengajuanMagang->lowongan->perusahaan->nama;
                })
                ->addColumn('status', function ($row) {
                    return $row->status . ' ' . $row->pengajuanMagang->lowongan->periodeMagang->nama;
                })
                ->addColumn('action', function ($row) {
                    $url = route('dosen.bimbingan-magang.monitoring', $row->pengajuanMagang->id);
                    $judul = $row->pengajuanMagang->lowongan->judul;
                    $perusahaan = $row->pengajuanMagang->lowongan->perusahaan->nama;
                    $mahasiswa = $row->pengajuanMagang->mahasiswa->nama;
                    $nim = $row->pengajuanMagang->mahasiswa->nim;
                    $status = $row->status;
                    $periode = $row->pengajuanMagang->lowongan->periodeMagang->nama;
                    $waktuMulai = $row->getWaktuMulaiMagangAttribute();
                    $sisaWaktu = $status === 'aktif'
                        ? ($row->getSisaWaktuMangangAttribute() . ' hari tersisa')
                        : ($waktuMulai > 0 ? ($waktuMulai . ' hari lagi akan dimulai') : '');

                    $statusBadge = StatusHelper::getMagangStatusBadge($status);
                    $badgeClass = $statusBadge['class'];
                    $icon = $status === 'selesai' ? 'check-circle' :
                        ($status === 'aktif' ? 'clock' : 'calendar');

                    return '
                    <a href="' . $url . '" class="text-decoration-none text-dark">
                        <div class="card card-hover cursor-pointer">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h3 class="mb-2 font-weight-bold">' . $judul . '</h3>
                                        <h5 class="mb-2 opacity-90">
                                            <i class="mdi mdi-city mr-2"></i>' . $perusahaan . '
                                        </h5>' .
                        ($sisaWaktu && $status !== 'selesai' ? '<p class="mb-2"><i class="mdi mdi-calendar-clock mr-2"></i>' . $sisaWaktu . '</p>' : '') . '
                                        <p class="mb-0">
                                            <i class="mdi mdi-account mr-2"></i> ' . $mahasiswa . ' (' . $nim . ')
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-right d-flex flex-column align-items-md-end">
                                        <span class="badge badge-' . $badgeClass . ' badge-lg px-3 py-2 mb-2">
                                            <i class="mdi mdi-' . $icon . ' mr-1"></i>' . $statusBadge['text'] . '
                                        </span>
                                        <p class="mb-0">' . $periode . '</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $breadcrumb = (object)[
            'title' => 'Bimbingan Magang Mahasiswa',
            'list' => ['Bimbingan Magang']
        ];
        $page = (object)[
            'title' => 'Bimbingan Magang Mahasiswa'
        ];

        return view('dosen.magang.index', compact('breadcrumb', 'page', 'data'));
    }

    public function monitoring($id, Request $request)
    {
        $data = PengajuanMagangModel::findOrFail($id);

        if ($request->ajax()) {
            $statusMagang = $data->magang->status ?? null;
            $data = $data->magang->logMagangMahasiswa->reverse()->values() ?? [];
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal', fn($row) => \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y'))
                ->editColumn('aktivitas', fn($row) => $row->aktivitas)
                ->editColumn('kendala', fn($row) => $row->kendala)
                ->editColumn('keterangan', fn($row) => $row->keterangan)
                ->addColumn('action', function ($row) use ($statusMagang) {

                    $lihatBtn = '';
                    if ($row->dokumentasi) {
                        $lihatBtn = '
            <button class="btn btn-sm btn-info" onclick="viewDoc(\'' . $row->getDokumenPath() . '\')">
                Lihat
            </button>
        ';
                    }
                    $evaluasiBtn = '';
                    if ($statusMagang === 'aktif') {
                        $tanggal = \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y');
                        $aktivitas = $row->aktivitas;
                        $evaluasiBtn = '
        <button class="btn btn-sm btn-warning" onclick="openEvaluasiBimbinganModal(\'' . $row->id . '\', \'' . $tanggal . '\', \'' . $aktivitas . '\')">
            Beri Evaluasi
        </button>
    ';
                    }
                    return '
        <div class="d-flex justify-content-between gap-2">
            ' . $lihatBtn . '
            ' . $evaluasiBtn . '
        </div>
    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $breadcrumb = (object)[
            'title' => 'Monitoring Magang Mahasiswa',
            'list' => ['Magang', 'Monitoring']
        ];
        $page = (object)[
            'title' => 'Monitoring Magang Mahasiswa'
        ];

        return view('dosen.magang.monitoring.index', compact('breadcrumb', 'page', 'data'));
    }

    public function storeEvaluasiBimbingan(Request $request)
    {
        $validated = $request->validate([
            'magang_id' => 'required|exists:t_magang,id',
            'tanggal_evaluasi' => 'required|date_format:d-m-Y',
            'catatan' => 'required|string|max:1000',
            'log_magang_mahasiswa_id' => 'nullable|exists:t_log_magang_mahasiswa,id',
        ]);

        $evaluasi = new EvaluasiBimbinganModel($validated);
        $evaluasi->save();

        return response()->json(['success' => true, 'message' => 'Evaluasi bimbingan berhasil disimpan.']);
    }

    public function destroyEvaluasiBimbingan($id)
    {
        $evaluasi = EvaluasiBimbinganModel::findOrFail($id);
        $evaluasi->delete();

        return response()->json(['success' => 'Evaluasi bimbingan berhasil dihapus.']);
    }

    public function downloadPdf($id)
    {
        $data = MagangModel::findOrFail($id);

        $pdf = Pdf::loadView('dosen.magang.monitoring.export-log-magang', compact('data'));

        return $pdf->download('logbook-magang.pdf');
    }
}
