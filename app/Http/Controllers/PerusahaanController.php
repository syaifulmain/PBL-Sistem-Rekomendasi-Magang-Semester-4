<?php

namespace App\Http\Controllers;

use App\Models\KecamatanModel;
use App\Models\LokasiPerusahaanModel;
use App\Models\PerusahaanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PerusahaanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Mitra Perusahaan',
            'list' => ['Perusahaan']
        ];
        $page = (object)[
            'title' => 'Managemen Mitra Perusahaan'
        ];

        return view('admin.perusahaan.index', compact('breadcrumb', 'page'));
    }

    public function list(Request $request)
    {
        try {
            $query = PerusahaanModel::select('id', 'nama', 'alamat');

            $totalData = $query->count();

            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('alamat', 'like', "%{$searchTerm}%");
            }

            $totalDataFiltered = $query->count();

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $data = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalDataFiltered,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $action = 'tambah';

        $breadcrumb = (object)[
            'title' => 'Mitra Perusahaan',
            'list' => ['Perusahaan', 'Tambah']
        ];
        $page = (object)[
            'title' => 'Tambah Perusahaan'
        ];

        return view('admin.perusahaan.action', compact('breadcrumb', 'page', 'action'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'website' => 'nullable|url|max:255',
                'email' => 'nullable|email|max:255',
                'no_telopon' => 'nullable|string|max:20',
                'provinsi_id' => 'required|integer',
                'provinsi_id-input' => 'required|string|max:255',
                'kabupaten_id' => 'required|integer',
                'kabupaten_id-input' => 'required|string|max:255',
                'kecamatan_id' => 'required|integer',
                'kecamatan_id-input' => 'required|string|max:255',
                'desa_id' => 'required|integer',
                'desa_id-input' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validasi gagal'
                ]);
            }

            $fullAddress = $request->input('alamat') . ', ' .
                $request->input('desa_id-input') . ', ' .
                $request->input('kecamatan_id-input') . ', ' .
                $request->input('kabupaten_id-input') . ', ' .
                $request->input('provinsi_id-input') . ', ' .
                'INDONESIA';

            $perushaan = PerusahaanModel::create([
                'nama' => $request->input('nama'),
                'alamat' => $fullAddress,
                'website' => $request->input('website'),
                'email' => $request->input('email'),
                'no_telepon' => $request->input('no_telopon')
            ]);

            LokasiPerusahaanModel::create([
                'perusahaan_id' => $perushaan->id,
                'negara_id' => 1,
                'provinsi_id' => $request->input('provinsi_id'),
                'kabupaten_id' => $request->input('kabupaten_id'),
                'kecamatan_id' => $request->input('kecamatan_id'),
                'desa_id' => $request->input('desa_id'),
                'alamat' => $request->input('alamat'),
                'longitude' => KecamatanModel::find($request->input('kecamatan_id'))->longitude ?? null,
                'latitude' => KecamatanModel::find($request->input('kecamatan_id'))->latitude ?? null
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Gagal menyimpan data'
            ]);
        }
    }

    public function edit($perusahaan_id)
    {
        $action = 'edit';

        return $this->action($perusahaan_id, $action);
    }

    public function detail($perusahaan_id)
    {
        $action = 'detail';

        return $this->action($perusahaan_id, $action);
    }

    public function action($perusahaan_id, $action)
    {
        $perusahaan = PerusahaanModel::find($perusahaan_id);
        $lokasiPerusahaan = LokasiPerusahaanModel::find($perusahaan_id);

        if (!$perusahaan) {
            return redirect()->route('admin.perusahaan.index')->with('error', 'Perusahaan tidak ditemukan');
        }

        $perusahaan->lokasi = $lokasiPerusahaan;

        $breadcrumb = (object)[
            'title' => 'Mitra Perusahaan',
            'list' => ['Perusahaan', ucfirst($action)]
        ];
        $page = (object)[
            'title' => ucfirst($action) . ' Perusahaan'
        ];

        return view('admin.perusahaan.action', compact('breadcrumb', 'page', 'perusahaan', 'action'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $perusahaan = PerusahaanModel::find($id);
            if (!$perusahaan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Perusahaan tidak ditemukan'
                ]);
            }

            $perusahaan->delete();
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Gagal menghapus data'
            ]);
        }
    }

    public function update(Request $request, $perusahaan_id)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'website' => 'nullable|url|max:255',
                'email' => 'nullable|email|max:255',
                'no_telopon' => 'nullable|string|max:20',
                'provinsi_id' => 'required|integer',
                'provinsi_id-input' => 'required|string|max:255',
                'kabupaten_id' => 'required|integer',
                'kabupaten_id-input' => 'required|string|max:255',
                'kecamatan_id' => 'required|integer',
                'kecamatan_id-input' => 'required|string|max:255',
                'desa_id' => 'required|integer',
                'desa_id-input' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Validasi gagal'
                ]);
            }

            $fullAddress = $request->input('alamat') . ', ' .
                $request->input('desa_id-input') . ', ' .
                $request->input('kecamatan_id-input') . ', ' .
                $request->input('kabupaten_id-input') . ', ' .
                $request->input('provinsi_id-input') . ', ' .
                'INDONESIA';

            $perusahaan = PerusahaanModel::find($perusahaan_id);
            if (!$perusahaan) {
                return response()->json([
                    'status' => false,
                    'message' => "Perusahaan tidak ditemukan"
                ]);
            }

            $perusahaan->update([
                'nama' => $request->input('nama'),
                'alamat' => $fullAddress,
                'website' => $request->input('website'),
                'email' => $request->input('email'),
                'no_telepon' => $request->input('no_telopon')
            ]);

            LokasiPerusahaanModel::where('perusahaan_id', $perusahaan_id)->update([
                'negara_id' => 1,
                'provinsi_id' => $request->input('provinsi_id'),
                'kabupaten_id' => $request->input('kabupaten_id'),
                'kecamatan_id' => $request->input('kecamatan_id'),
                'desa_id' => $request->input('desa_id'),
                'alamat' => $request->input('alamat')
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Gagal memperbarui data'
            ]);
        }
    }
}
