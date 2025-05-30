<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\LogMagangMahasiswaModel;
use App\Models\MagangModel;
use App\Models\PengajuanMagangModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MagangMahasiswaController extends Controller
{
    public function index()
    {
        $data = PengajuanMagangModel::where('mahasiswa_id', auth()->user()->mahasiswa->id)
            ->where('status', '=', 'disetujui')
            ->get();

        $breadcrumb = (object)[
            'title' => 'Magang Mahasiswa',
            'list' => ['Magang']
        ];
        $page = (object)[
            'title' => 'Managemen Magang Mahasiswa'
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
