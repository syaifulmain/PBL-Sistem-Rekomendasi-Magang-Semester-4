<?php

namespace App\Http\Controllers;

use App\Constants\RegexPatterns;
use App\Models\DesaModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\LokasiPerusahaanModel;
use App\Models\PerusahaanModel;
use App\Models\ProvinsiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PerusahaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PerusahaanModel::query();

            return DataTables::of($data)
                ->editColumn('nama', fn($row) => $row->nama)
                ->editColumn('alamat', fn($row) => $row->alamat)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.mitra-perusahaan.edit', $row->id);
                    $deleteUrl = route('admin.mitra-perusahaan.delete', $row->id);

                    return view('components.action-buttons', compact('editUrl', 'deleteUrl'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Mitra Perusahaan';
        $breadcrumb = [
            'title' => 'Mitra Perusahaan',
            'list' => ['Mitra Perusahaan']
        ];
        return view('admin.mitra_perusahaan.index', compact('title', 'breadcrumb'));
    }

    public function create()
    {
        $title = 'Mitra Perusahaan';
        $breadcrumb = [
            'title' => 'Mitra Perusahaan',
            'list' => ['Mitra Perusahaan', 'Tambah']
        ];
        return view('admin.mitra_perusahaan.form', compact('title', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255|regex:' . RegexPatterns::SAFE_INPUT . '|unique:m_perusahaan,nama',
            'provinsi_id' => 'required|exists:m_provinsi,id',
            'kabupaten_id' => 'required|exists:m_kabupaten,id',
            'kecamatan_id' => 'required|exists:m_kecamatan,id',
            'desa_id' => 'required|exists:m_desa,id',
            'alamat' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|max:255',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {

            $perusahaan = PerusahaanModel::create([
                'nama' => $validated['nama'],
                'alamat' => ProvinsiModel::find($validated['provinsi_id'])->nama . ', ' .
                    KabupatenModel::find($validated['kabupaten_id'])->nama . ', ' .
                    KecamatanModel::find($validated['kecamatan_id'])->nama . ', ' .
                    DesaModel::find($validated['desa_id'])->nama . ', ' .
                    $validated['alamat'],
                'website' => $validated['website'] ?? null,
                'email' => $validated['email'] ?? null,
                'no_telepon' => $validated['no_telepon'] ?? null,
            ]);

            LokasiPerusahaanModel::create([
                'perusahaan_id' => $perusahaan->id,
                'negara_id' => 1, // Assuming 1 is the ID for Indonesia
                'provinsi_id' => $validated['provinsi_id'],
                'kabupaten_id' => $validated['kabupaten_id'],
                'kecamatan_id' => $validated['kecamatan_id'],
                'desa_id' => $validated['desa_id'],
                'alamat' => $validated['alamat'],
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Perusahaan berhasil ditambahkan.',
                'redirect' => route('admin.mitra-perusahaan.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan perusahaan.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function edit(string $id)
    {
        $data = PerusahaanModel::findOrFail($id);
        $title = 'Mitra Perusahaan';
        $breadcrumb = [
            'title' => 'Mitra Perusahaan',
            'list' => ['Mitra Perusahaan', 'Edit']
        ];
        return view('admin.mitra_perusahaan.form', compact('title', 'breadcrumb', 'data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'max:255',
                'regex:' . RegexPatterns::SAFE_INPUT,
                Rule::unique('m_perusahaan', 'nama')->ignore($id),
            ],
            'provinsi_id' => 'required|exists:m_provinsi,id',
            'kabupaten_id' => 'required|exists:m_kabupaten,id',
            'kecamatan_id' => 'required|exists:m_kecamatan,id',
            'desa_id' => 'required|exists:m_desa,id',
            'alamat' => 'required|regex:' . RegexPatterns::SAFE_INPUT. '|max:255',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $perusahaan = PerusahaanModel::findOrFail($id);

            $perusahaan->update([
                'nama' => $validated['nama'],
                'alamat' => ProvinsiModel::find($validated['provinsi_id'])->nama . ', ' .
                    KabupatenModel::find($validated['kabupaten_id'])->nama . ', ' .
                    KecamatanModel::find($validated['kecamatan_id'])->nama . ', ' .
                    DesaModel::find($validated['desa_id'])->nama . ', ' .
                    $validated['alamat'],
                'website' => $validated['website'] ?? null,
                'email' => $validated['email'] ?? null,
                'no_telepon' => $validated['no_telepon'] ?? null,
            ]);

            LokasiPerusahaanModel::updateOrCreate(
                ['perusahaan_id' => $perusahaan->id],
                [
                    'negara_id' => 1, // Assuming 1 is the ID for Indonesia
                    'provinsi_id' => $validated['provinsi_id'],
                    'kabupaten_id' => $validated['kabupaten_id'],
                    'kecamatan_id' => $validated['kecamatan_id'],
                    'desa_id' => $validated['desa_id'],
                    'alamat' => $validated['alamat'],
                ]
            );

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Perusahaan berhasil diperbarui.',
                'redirect' => route('admin.mitra-perusahaan.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui perusahaan.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $perusahaan = PerusahaanModel::findOrFail($id);
            $perusahaan->delete();

            DB::commit();
            return response()->json(['success' => 'Data berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

    public function getProvinsi(Request $request, $id = 1)
    {
        $search = $request->input('q');

        $data = ProvinsiModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->where('negara_id', $id)
            ->select('id', 'nama as text')
            ->limit(20)
            ->get();

        return response()->json($data);
    }

    public function getKabupaten(Request $request, $id)
    {
        $search = $request->input('q');

        $data = KabupatenModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->where('provinsi_id', $id)
            ->select('id', 'nama as text')
            ->limit(20)
            ->get();

        return response()->json($data);
    }

    public function getKecamatan(Request $request, $id)
    {
        $search = $request->input('q');

        $data = KecamatanModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->where('kabupaten_id', $id)
            ->select('id', 'nama as text')
            ->limit(20)
            ->get();

        return response()->json($data);
    }

    public function getDesa(Request $request, $id)
    {
        $search = $request->input('q');

        $data = DesaModel::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->where('kecamatan_id', $id)
            ->select('id', 'nama as text')
            ->limit(20)
            ->get();

        return response()->json($data);
    }
}
