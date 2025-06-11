<?php

namespace App\Http\Controllers;

use App\Exceptions\RedirectException;
use App\Models\DokumenPengajuanModel;
use App\Models\LowonganMagangModel;
use App\Models\PengajuanMagangModel;
use App\Notifications\PengajuanMagangNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PengajuanMagangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PengajuanMagangModel::with(['mahasiswa', 'lowongan.perusahaan'])->where('mahasiswa_id', auth()->user()->mahasiswa->id);

            return DataTables::of($data)
                ->addColumn('judul_lowongan', fn($row) => $row->lowongan->judul)
                ->addColumn('perusahaan', fn($row) => $row->lowongan->perusahaan->nama ?? '-')
                ->addColumn('action', function ($row) {
                    return view('mahasiswa.pengajuan_magang._action', compact('row'))->render();
                })
                ->editColumn('tanggal_pengajuan', function ($row) {
                    return Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d F Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Pengajuan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];

        return view('mahasiswa.pengajuan_magang.index', compact('title', 'breadcrumb'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'exists:t_lowongan_magang,id',
        ]);

        $title = 'Buat Pengajuan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];

        $lowonganId = null;

        try {
            $lowonganId = $request->get('lowongan_id');
            $this->verifiyApplication($lowonganId);
            $this->verifiyDateStatus($lowonganId);
        } catch (\Illuminate\Http\RedirectResponse $redirect) {
            return $redirect;
        }

        return view('mahasiswa.pengajuan_magang.form', compact('title', 'breadcrumb', 'lowonganId'));
    }
    
   public function store(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:t_lowongan_magang,id',
            'dokumen.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $this->verifiyApplication($request->lowongan_id);
            $this->verifiyDateStatus($request->lowongan_id);
        } catch (\Illuminate\Http\RedirectResponse $redirect) {
            return $redirect;
        }

        DB::beginTransaction();
        try {
            $pengajuan = new PengajuanMagangModel();
            $pengajuan->mahasiswa_id = auth()->user()->mahasiswa->id;
            $pengajuan->lowongan_magang_id = $request->lowongan_id;
            $pengajuan->status = 'diajukan';
            $pengajuan->tanggal_pengajuan = now();
            $pengajuan->save();
    
            $dokumen = [];
            foreach ($request->dokumen as $kode => $file) {
                $fileName = time() . '_' . $kode . '_' . $file->getClientOriginalName();
                $path = $file->storeAs("public/dokumen_magang/{$pengajuan->id}", $fileName);
    
                $dokumen[] = [
                    'pengajuan_magang_id' => $pengajuan->id,
                    'jenis_dokumen_id' => $kode,
                    'path' => $path
                ];
            }
            if (!empty($dokumen)) {
                DokumenPengajuanModel::insert($dokumen);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }

        return redirect()->route('mahasiswa.pengajuan-magang.index')->with('success', 'Pengajuan berhasil diajukan.');
    }

    public function show($id)
    {
        $data = PengajuanMagangModel::with(['mahasiswa', 'lowongan.perusahaan', 'dokumen.jenisDokumen'])->findOrFail($id);

        $title = 'Detail Pengajuan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];
        
        return view('mahasiswa.pengajuan_magang.detail', compact('data', 'title', 'breadcrumb'));
    }

    public function destroy($id)
    {
        $pengajuan = PengajuanMagangModel::findOrFail($id);

        $pengajuan->update([
            'status' => 'batal'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan berhasil dibatalkan.'
        ]);
    }

    public function getLowongan(Request $request)
    {
        $search = $request->input('q');
        $id = $request->input('id');

        $data = LowonganMagangModel::with('perusahaan')
            ->where('status', 'buka')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('perusahaan', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    });
                });
            })
            ->when($id, function ($query, $id) {
                return $query->where('id', $id);
            })
            ->limit(20)
            ->get();

        $data->transform(function ($item) {
            $item->text = $item->judul . ' - ' . ($item->perusahaan->nama ?? '-');
            return $item;
        });

        return response()->json($data);
    }

    public function getLowonganDokumen($id)
    {
        $lowongan = LowonganMagangModel::with('dokumen')->findOrFail($id);
        return response()->json($lowongan->dokumen);
    }

    private function verifiyApplication($lowonganId)
    {
        $pengajuan = PengajuanMagangModel::where('lowongan_magang_id', $lowonganId)
            ->where('mahasiswa_id', auth()->user()->mahasiswa->id)
            ->whereNot('status', 'batal')
            ->first();

        if ($pengajuan) {
            throw new RedirectException(
                redirect()->back()->with('error', 'Anda sudah mengajukan pengajuan magang ini')
            );
        }
    }

    private function verifiyDateStatus($lowonganId)
    {
        $lowongan = LowonganMagangModel::findOrFail($lowonganId);
        $today = now()->startOfDay();

        if ($today->lt($lowongan->tanggal_mulai_daftar) || $today->gt($lowongan->tanggal_selesai_daftar)) {
            throw new RedirectException(
                redirect()->back()->with('error', 'Pendaftaran lowongan ini hanya dibuka antara ' .
                    $lowongan->tanggal_mulai_daftar->translatedFormat('d F Y') . ' dan ' .
                    $lowongan->tanggal_selesai_daftar->translatedFormat('d F Y'))
            );
        }

        if ($lowongan->status !== 'buka') {
            throw new RedirectException(
                redirect()->back()->with('error', 'Lowongan ini tidak terbuka')
            );
        }
    }
}
