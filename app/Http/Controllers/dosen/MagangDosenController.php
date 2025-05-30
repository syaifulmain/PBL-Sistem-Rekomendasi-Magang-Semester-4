<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use App\Models\EvaluasiBimbinganModel;
use App\Models\MagangModel;
use App\Models\PengajuanMagangModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MagangDosenController extends Controller
{
    public function index()
    {
        $data = MagangModel::where('dosen_id', auth()->user()->dosen->id)->get();

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
                    $deleteUrl = route('mahasiswa.evaluasi-magang.monitoring.log.delete', $row->id);

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
            'tanggal_evaluasi' => 'required|date_format:Y-m-d',
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
