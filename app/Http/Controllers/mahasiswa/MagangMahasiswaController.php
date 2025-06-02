<?php

namespace App\Http\Controllers\mahasiswa;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Models\LogMagangMahasiswaModel;
use App\Models\MagangModel;
use App\Models\PengajuanMagangModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MagangMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $data = PengajuanMagangModel::where('mahasiswa_id', auth()->user()->mahasiswa->id)
            ->where('status', '=', 'disetujui')
            ->orderBy('status', 'asc')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $judul = $row->lowongan->judul ?? '-';
                    $perusahaan = $row->lowongan->perusahaan->nama ?? '-';
                    $statusMagang = $row->magang->status;
                    $periodeMagang = $row->lowongan->periodeMagang->nama ?? '-';
                    
                    // Tentukan badge class dan icon berdasarkan status
                    $badgeClass = StatusHelper::getMagangStatusBadge($statusMagang);
                    
                    $icon = match($statusMagang) {
                        'selesai' => 'check-circle',
                        'aktif' => 'clock',
                        'belum_dimulai' => 'calendar',
                        default => 'info-circle'
                    };
                    
                    // Hitung waktu tersisa atau waktu hingga dimulai
                    $waktuMulai = $row->magang->getWaktuMulaiMagangAttribute();
                    $sisaWaktu = $statusMagang === 'aktif' 
                        ? ($row->magang->getSisaWaktuMangangAttribute() . ' hari tersisa') 
                        : ($waktuMulai > 0 ? ($waktuMulai . ' hari lagi akan dimulai') : '');
                    
                    $dosen = $row->magang->dosen->nama ?? '-';
                    $route = route('mahasiswa.evaluasi-magang.monitoring', $row->id);
                    $statusText = ucfirst(str_replace('_', ' ', $statusMagang));
    
                    return '
                    <a href="' . $route . '" class="text-decoration-none text-dark">
                        <div class="card mb-3 card-hover cursor-pointer">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h3 class="mb-2 font-weight-bold">' . $judul . '</h3>
                                        <h5 class="mb-2 opacity-90">
                                            <i class="mdi mdi-city mr-2"></i>' . $perusahaan . '
                                        </h5>' .
                        ($sisaWaktu ? '
                                        <p class="mb-2">
                                            <i class="mdi mdi-calendar-clock mr-2"></i>' . $sisaWaktu . '
                                        </p>' : '') . '
                                        <p class="mb-0">
                                            <i class="mdi mdi-tie mr-2"></i> Pembimbing: ' . $dosen . '
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-right d-flex flex-column align-items-md-end">
                                        <span class="badge badge-' . $badgeClass . ' badge-lg px-3 py-2 mb-2">
                                            <i class="mdi mdi-' . $icon . ' mr-1"></i>' . $statusText . '
                                        </span>
                                        <p class="mb-0">' . $periodeMagang . '</p>
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
            'title' => 'Magang Mahasiswa',
            'list' => ['Magang']
        ];
        $page = (object)[
            'title' => 'Manajemen Magang Mahasiswa'
        ];

        return view('mahasiswa.magang.index', compact('breadcrumb', 'page', 'data'));
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
                    $deleteUrl = route('mahasiswa.evaluasi-magang.monitoring.log.delete', $row->id);

                    $lihatBtn = '';
                    if ($row->dokumentasi) {
                        $lihatBtn = '
            <button class="btn btn-sm btn-info" onclick="viewDoc(\'' . $row->getDokumenPath() . '\')">
                Lihat
            </button>
        ';
                    }
                    $hapusBtn = '';
                    if ($statusMagang === 'aktif') {

                        $hapusBtn = '
        <button class="btn btn-sm btn-danger btn-delete" data-url="' . $deleteUrl . '">
            Hapus
        </button>
    ';
                    }

                    return '
        <div class="d-flex justify-content-between gap-2">
            ' . $lihatBtn . '
            ' . $hapusBtn . '
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

        return view('mahasiswa.magang.monitoring.index', compact('breadcrumb', 'page', 'data'));
    }

    public function storeLogMagang(Request $request, $id)
    {
        try {
            $magang = MagangModel::findOrFail($id);
            $logData = $request->validate([
                'tanggal' => 'required|date',
                'aktivitas' => 'required|string',
                'kendala' => 'nullable|string',
                'keterangan' => 'nullable|string',
                'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('dokumentasi')) {
                $randomName = uniqid() . '.' . $request->file('dokumentasi')->getClientOriginalExtension();
                $logData['dokumentasi'] = $request->file('dokumentasi')->storeAs('magang/log', $randomName, 'public');
            }

            $magang->logMagangMahasiswa()->create($logData);

            return response()->json(['success' => true, 'message' => 'Log magang berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroyLogMagang($id)
    {
        try {
            $log = LogMagangMahasiswaModel::findOrFail($id);
            $log->delete();

            return response()->json(['success' => 'Data berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function downloadPdf($id)
    {
        $data = MagangModel::findOrFail($id);

        $pdf = Pdf::loadView('mahasiswa.magang.monitoring.export-log-magang', compact('data'));

        return $pdf->download('logbook-magang.pdf');
    }

    public function storeEvaluasiMagang(Request $request, $id)
    {
        $validated = $request->validate([
            'magang_id' => 'required|exists:t_magang,id',
            'sertifikat_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'umpan_balik_mahasiswa' => 'required|string|max:1000',
        ]);

        if ($request->hasFile('sertifikat_path')) {
            $randomName = uniqid() . '.' . $request->file('sertifikat_path')->getClientOriginalExtension();
            $validated['sertifikat_path'] = $request->file('sertifikat_path')->storeAs('magang/sertifikat', $randomName, 'public');
        }

        $evaluasi = MagangModel::findOrFail($validated['magang_id']);
        $evaluasi->evaluasiMagangMahasiswa()->create($validated);

        return response()->json(['success' => true, 'message' => 'Evaluasi magang berhasil disimpan.']);
    }
}
