<?php

namespace App\Http\Controllers;

use App\Enums\LevelTeknis;
use App\Http\Controllers\Controller;
use App\Models\BidangKeahlianModel;
use App\Models\LowonganMagangModel;
use App\Models\DokumenLowonganModel;
use App\Models\JenisDokumenModel;
use App\Models\BidangKeahlianLowonganModel;
use App\Models\KeahlianTeknisLowonganModel;
use App\Models\KeahlianTeknisModel;
use App\Models\PeriodeMagangModel;
use App\Models\PerusahaanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class LowonganMagangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = LowonganMagangModel::with(['perusahaan', 'periodeMagang']);

            return DataTables::of($data)
                ->addColumn('perusahaan', fn($row) => $row->perusahaan->nama ?? '-')
                ->addColumn('periode_magang', fn($row) => $row->periodeMagang->nama ?? '-')
                ->editColumn('tanggal_mulai_daftar', fn($row) => Carbon::parse($row->tanggal_mulai_daftar)->translatedFormat('d F Y'))
                ->editColumn('tanggal_selesai_daftar', fn($row) => Carbon::parse($row->tanggal_selesai_daftar)->translatedFormat('d F Y'))
                ->editColumn('tanggal_mulai_magang', fn($row) => Carbon::parse($row->tanggal_mulai_magang)->translatedFormat('d F Y'))
                ->editColumn('tanggal_selesai_magang', fn($row) => Carbon::parse($row->tanggal_selesai_magang)->translatedFormat('d F Y'))
                ->addColumn('action', function ($row) {
                    $detailUrl = route('admin.lowongan-magang.show', $row->id);
                    $editUrl = route('admin.lowongan-magang.edit', $row->id);
                    $deleteUrl = route('admin.lowongan-magang.delete', $row->id);
                    return '
                        <a href="'.$detailUrl.'" class="btn btn-info btn-sm">Detail</a>
                        <a href="'.$editUrl.'" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm btn-delete" data-url="'.$deleteUrl.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Lowongan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title]
        ];

        return view('admin.lowongan_magang.index', compact('title', 'breadcrumb'));
    }

    public function show($id)
    {
        $data = LowonganMagangModel::with(['perusahaan', 'periodeMagang', 'keahlian', 'dokumen'])->findOrFail($id);
        $title = 'Detail Lowongan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => ['Lowongan Magang', 'Detail']
        ];
        return view('admin.lowongan_magang.detail', compact('data', 'title', 'breadcrumb'));
    }


    public function create()
    {
        $title = 'Tambah Lowongan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => ['Lowongan Magang', 'Tambah']
        ];
        $levelKeahlianTeknis = LevelTeknis::cases();
        return view('admin.lowongan_magang.form', compact('title', 'breadcrumb', 'levelKeahlianTeknis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id'          => 'required|exists:m_perusahaan,id',
            'periode_magang_id'      => 'required|exists:m_periode_magang,id',
            'judul'                  => 'required|string|max:100',
            'deskripsi'              => 'required|string',
            'persyaratan'            => 'nullable|string',
            'kuota'                  => 'required|integer|min:1',
            'minimal_ipk' => 'required|numeric|min:0|max:4',
            'insentif' => 'nullable|string|max:255',
            'tanggal_mulai_daftar'   => 'required|date',
            'tanggal_selesai_daftar' => 'required|date|after_or_equal:tanggal_mulai_daftar',
            'tanggal_mulai_magang'   => 'required|date|after_or_equal:tanggal_selesai_daftar',
            'tanggal_selesai_magang' => 'required|date|after_or_equal:tanggal_mulai_magang',
            'status'                 => ['required', Rule::in(['buka', 'tutup', 'dibatalkan'])],
            'keahlian_ids' => 'required|array',
            'keahlian_ids.*' => 'exists:m_bidang_keahlian,id',
            'dokumen_ids' => 'required|array',
            'dokumen_ids.*' => 'exists:m_jenis_dokumen,id',
            'keahlian_teknis_ids' => 'required|array',
            'keahlian_teknis_ids.*' => 'exists:m_keahlian_teknis,id',
            'keahlian_teknis_levels.*' => [Rule::in(LevelTeknis::cases())],
        ]);

        DB::transaction(function() use ($validated) {
            $lowongan = LowonganMagangModel::create($validated);

           if (!empty($validated['keahlian_ids'])) {
                foreach ($validated['keahlian_ids'] as $bidangKeahlianId) {
                    $keahlians[] = [
                        'lowongan_magang_id' => $lowongan->id,
                        'bidang_keahlian_id' => $bidangKeahlianId,
                    ];
                }
                BidangKeahlianLowonganModel::insert($keahlians);
            }

            if (!empty($validated['dokumen_ids'])) {
                foreach ($validated['dokumen_ids'] as $jenisDokumenId) {
                    $dokumens[] = [
                        'lowongan_magang_id' => $lowongan->id,
                        'jenis_dokumen_id' => $jenisDokumenId,
                    ];
                }
                DokumenLowonganModel::insert($dokumens);
            }

            if (!empty($validated['keahlian_teknis_ids'])) {
                foreach ($validated['keahlian_teknis_ids'] as $index => $id) {
                    $level = $validated['keahlian_teknis_levels'][$index];

                    $teknis[] = [
                        'lowongan_magang_id' => $lowongan->id,
                        'keahlian_teknis_id' => $id,
                        'level' => $level,
                    ];
                }
                KeahlianTeknisLowonganModel::insert($teknis);
            }
        });

        return redirect()->route('admin.lowongan-magang.index')->with('success', 'Lowongan magang berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $data = LowonganMagangModel::with(['dokumen', 'keahlian', 'teknis'])->findOrFail($id);

        $title = 'Edit Lowongan Magang';
        $breadcrumb = [
            'title' => $title,
            'list' => ['Lowongan Magang', 'Edit']
        ];

        $levelKeahlianTeknis = LevelTeknis::cases();

        return view('admin.lowongan_magang.form', compact('data', 'title', 'breadcrumb', 'levelKeahlianTeknis'));
    }

    public function update(Request $request, string $id)
    {
        $lowongan = LowonganMagangModel::findOrFail($id);

        $validated = $request->validate([
            'perusahaan_id'          => 'required|exists:m_perusahaan,id',
            'periode_magang_id'      => 'required|exists:m_periode_magang,id',
            'judul'                  => 'required|string|max:100',
            'deskripsi'              => 'required|string',
            'persyaratan'            => 'nullable|string',
            'kuota'                  => 'required|integer|min:1',
            'minimal_ipk' => 'required|numeric|min:0|max:4',
            'insentif' => 'nullable|string|max:255',
            'tanggal_mulai_daftar'   => 'required|date',
            'tanggal_selesai_daftar' => 'required|date|after_or_equal:tanggal_mulai_daftar',
            'tanggal_mulai_magang'   => 'required|date|after_or_equal:tanggal_selesai_daftar',
            'tanggal_selesai_magang' => 'required|date|after_or_equal:tanggal_mulai_magang',
            'status'                 => ['required', Rule::in(['buka', 'tutup', 'dibatalkan'])],
            'keahlian_ids' => 'required|array',
            'keahlian_ids.*' => 'exists:m_bidang_keahlian,id',
            'dokumen_ids' => 'required|array',
            'dokumen_ids.*' => 'exists:m_jenis_dokumen,id',
            'keahlian_teknis_ids' => 'required|array',
            'keahlian_teknis_ids.*' => 'exists:m_keahlian_teknis,id',
            'keahlian_teknis_levels.*' => [Rule::in(LevelTeknis::cases())],
        ]);

        DB::transaction(function() use ($validated, $lowongan) {
            $lowongan->update($validated);

            $lowongan->keahlian()->detach();
            $lowongan->dokumen()->detach();
            $lowongan->teknis()->detach();

            if (!empty($validated['keahlian_ids'])) {
                foreach ($validated['keahlian_ids'] as $bidangKeahlianId) {
                    $keahlians[] = [
                        'lowongan_magang_id' => $lowongan->id,
                        'bidang_keahlian_id' => $bidangKeahlianId,
                    ];
                }
                BidangKeahlianLowonganModel::insert($keahlians);
            }

            if (!empty($validated['dokumen_ids'])) {
                foreach ($validated['dokumen_ids'] as $jenisDokumenId) {
                    $dokumens[] = [
                        'lowongan_magang_id' => $lowongan->id,
                        'jenis_dokumen_id' => $jenisDokumenId,
                    ];
                }
                DokumenLowonganModel::insert($dokumens);
            }
            
            if (!empty($validated['keahlian_teknis_ids'])) {
                foreach ($validated['keahlian_teknis_ids'] as $index => $id) {
                    $level = $validated['keahlian_teknis_levels'][$index];

                    $teknis[] = [
                        'lowongan_magang_id' => $lowongan->id,
                        'keahlian_teknis_id' => $id,
                        'level' => $level,
                    ];
                }
                KeahlianTeknisLowonganModel::insert($teknis);
            }
        });

        return redirect()->route('admin.lowongan-magang.index')->with('success', 'Lowongan magang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $lowongan = LowonganMagangModel::findOrFail($id);

        DB::transaction(function() use ($lowongan) {
            $lowongan->delete();
        });

        return response()->json(['success' => 'Data berhasil dihapus.']);
    }

    public function getPerusahaan(Request $request)
    {
        $search = $request->input('q');

        $data = PerusahaanModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->select('id', 'nama as text')->limit(20)->get();

        return response()->json($data);
    }

    public function getPeriodeMagang(Request $request)
    {
        $search = $request->input('q');

        $data = PeriodeMagangModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->select('id', 'nama as text')->limit(20)->get();

        return response()->json($data);
    }

    public function getKeahlian(Request $request)
    {
        $search = $request->input('q');

        $data = BidangKeahlianModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->select('id', 'nama as text')->limit(20)->get();

        return response()->json($data);
    }

    public function getDokumen(Request $request)
    {
        $search = $request->input('q');

        $data = JenisDokumenModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->select('id', 'nama as text')->limit(20)->get();

        return response()->json($data);
    }
    
    public function getKeahlianTeknis(Request $request)
    {
        $search = $request->input('q');

        $data = KeahlianTeknisModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->select('id', 'nama as text')->limit(20)->get();

        return response()->json($data);
    }
}
