<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\MagangModel;
use App\Models\PengajuanMagangModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class KegiatanMagangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PengajuanMagangModel::with(['mahasiswa', 'lowongan.perusahaan'])->where('t_pengajuan_magang.status', 'diajukan');

            return DataTables::of($data)
                ->addColumn('mahasiswa', fn($row) => $row->mahasiswa->nama ?? '-')
                ->addColumn('nim', fn($row) => $row->mahasiswa->nim ?? '-')
                ->addColumn('lowongan', fn($row) => $row->lowongan->judul ?? '-')
                ->addColumn('perusahaan', fn($row) => $row->lowongan->perusahaan->nama ?? '-')
                ->editColumn('tanggal_pengajuan', fn($row) => Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d F Y'))
                ->addColumn('action', function ($row) {
                    $prosesUrl = route('admin.kegiatan-magang.process', $row->id);
                    return view('components.action-buttons', compact('prosesUrl'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Kegiatan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];

        return view('admin.kegiatan_magang.index', compact('title', 'breadcrumb'));
    }

    public function process($id)
    {
        $pengajuan = PengajuanMagangModel::with(['mahasiswa', 'lowongan.perusahaan', 'dokumen.jenisDokumen'])->findOrFail($id);
        $dosen = DosenModel::whereNull('deleted_at')->get();

        $title = 'Proses Kegiatan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];
        return view('admin.kegiatan_magang.form', compact('pengajuan', 'dosen', 'title', 'breadcrumb'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'dosen_id' => 'required_if:status,disetujui',
            'catatan' => 'required_if:status,ditolak'
        ]);

        $pengajuan = PengajuanMagangModel::findOrFail($id);
        $mahasiswa = $pengajuan->mahasiswa;
        $user = UserModel::findOrFail($mahasiswa->user_id);

        DB::beginTransaction();
            try {
            $pengajuan->update([
                'status' => $request->status,
                'catatan' => $request->catatan
            ]);

            if ($request->status === 'disetujui') {
                $magang = MagangModel::create([
                    'pengajuan_magang_id' => $pengajuan->id,
                    'dosen_id' => $request->dosen_id,
                    'status' => 'aktif',
                    'tanggal_mulai' => $pengajuan->lowongan->tanggal_mulai_magang,
                    'tanggal_selesai' => $pengajuan->lowongan->tanggal_selesai_magang,
                ]);

                // Notifikasi untuk dosen
                $dosen = DosenModel::findOrFail($request->dosen_id);
                NotificationController::createNotification(
                    $dosen->user_id,
                    'Mahasiswa bimbingan baru: ' . $mahasiswa->nama . ' (' . $mahasiswa->nim . ')',
                    'Bimbingan Magang Mahasiswa',
                    url()->route('dosen.bimbingan-magang.monitoring', $magang->id, false)
                );
            }

            // Notifikasi untuk mahasiswa
            NotificationController::createNotification(
                $user->id,
                match($request->status) {
                    'disetujui' => 'Pengajuan magang anda disetujui',
                    'ditolak' => 'Pengajuan magang anda ditolak',
                },
                'Pengajuan Magang',
                url()->route('mahasiswa.pengajuan-magang.show', $pengajuan->id, false)
            );

            DB::commit();
            return redirect()->route('admin.kegiatan-magang.index')->with('success', 'Pengajuan berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }
}
