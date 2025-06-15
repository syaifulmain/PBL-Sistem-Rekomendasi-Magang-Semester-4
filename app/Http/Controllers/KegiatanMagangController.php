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
        $dosen = DosenModel::with('minat.bidangKeahlian')->whereNull('deleted_at')->get();

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

        $pengajuan = PengajuanMagangModel::with(['lowongan.periodeMagang'])->findOrFail($id);
        $mahasiswa = $pengajuan->mahasiswa;
        $user = UserModel::findOrFail($mahasiswa->user_id);
        $kuota = $pengajuan->lowongan->kuota;
        $terima = PengajuanMagangModel::where('lowongan_magang_id', $pengajuan->lowongan_magang_id)->where('status', 'disetujui')->count();


        if ($request->status === 'disetujui') {
            if ($kuota <= $terima) {
                return redirect()->back()->with('error', 'Kuota lowongan magang ini sudah penuh');
            }
            if ($pengajuan->mahasiswa->ipk < $pengajuan->lowongan->minimal_ipk) {
                return redirect()->back()->with('error', 'IPK mahasiswa tidak memenuhi syarat');
            }
        }

        DB::beginTransaction();
            try {
            $pengajuan->update([
                'status' => $request->status,
                'catatan' => $request->catatan
            ]);

            if ($request->status === 'disetujui') {
                $samePeriodePengajuan = PengajuanMagangModel::join('t_lowongan_magang', 't_pengajuan_magang.lowongan_magang_id', '=', 't_lowongan_magang.id')
                ->where('t_pengajuan_magang.mahasiswa_id', $mahasiswa->id)
                ->where('t_lowongan_magang.periode_magang_id', $pengajuan->lowongan->periode_magang_id)
                ->whereNot('t_pengajuan_magang.id', $pengajuan->id);

                if ($samePeriodePengajuan->exists()) {
                    $samePeriodePengajuan->update([
                        't_pengajuan_magang.status' => 'batal',
                        't_pengajuan_magang.catatan' => '(Otomatis) Mahasiswa sudah terdaftar magang di lowongan magang lain dalam periode magang yang sama'
                    ]);
                }

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
                    url()->route('dosen.bimbingan-magang.monitoring', $pengajuan->id, false)
                );
                // Notifikasi untuk mahasiswa
                NotificationController::createNotification(
                    $user->id,
                    match($request->status) {
                        'disetujui' => 'Pengajuan magang anda disetujui',
                        'ditolak' => 'Pengajuan magang anda ditolak',
                    },
                    'Pengajuan Magang',
                    url()->route('mahasiswa.evaluasi-magang.monitoring', $pengajuan->id, false)
                );
            } else {
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
            }

            DB::commit();
            return redirect()->route('admin.kegiatan-magang.index')->with('success', 'Pengajuan berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }
}
