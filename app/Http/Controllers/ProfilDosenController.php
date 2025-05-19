<?php

namespace App\Http\Controllers;

use App\Models\BidangKeahlianModel;
use App\Models\DesaModel;
use App\Models\DosenModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\MinatDosenModel;
use App\Models\PreferensiLokasiDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilDosenController extends Controller
{
    public function index()
    {
        $data = DosenModel::with('minatDosen', 'preferensiLokasiDosen')
            ->where('id', auth()->user()->dosen->id)->first();
        $title = 'Profil Pengguna';
        $breadcrumb = [
            'title' => 'Profil Pengguna',
            'list' => ['Profil Pengguna']
        ];
        return view('profil.dosen.index', compact('title', 'breadcrumb', 'data'));
    }

    public function editInformasiPengguna()
    {
        $title = 'Edit Informasi Pengguna';
        $informasiPengguna = DosenModel::where('id', auth()->user()->dosen->id)->first();
        return view('profil.dosen.form-informasi-pengguna', compact('title', 'informasiPengguna'));
    }

    public function updateInformasiPengguna(Request $request)
    {
        try {
            DB::beginTransaction();

            $dosen = DosenModel::where('id', auth()->user()->dosen->id)->first();
            $request->validate([
                'alamat' => 'nullable|string|max:255',
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $dosen->alamat = $request->alamat;

            if ($request->hasFile('foto_profil')) {
                $file = $request->file('foto_profil');
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid('profile_') . '_' . time() . '.' . $extension;
                $file->storeAs('public/foto_profil', $fileName);
                $dosen->user->path_foto_profil = 'foto_profil/' . $fileName;
                $dosen->user->save();
            }

            $dosen->save();

            DB::commit();

            return redirect()->route('dosen.profil.index')->with('success', 'Informasi pengguna berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();


            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editInformasiDetail()
    {
        $title = 'Edit Informasi Detail';
        $informasiDetail = DosenModel::where('id', auth()->user()->dosen->id)->first();
        return view('profil.dosen.form-informasi-detail', compact('title', 'informasiDetail'));
    }

    public function updateInformasiDetail(Request $request)
    {
        try {
            DB::beginTransaction();

            $dosen = DosenModel::where('id', auth()->user()->dosen->id)->first();
            $request->validate([
                'no_telepon' => 'required|string|max:15',
            ]);

            $dosen->no_telepon = $request->no_telepon;
            $dosen->save();

            DB::commit();

            return redirect()->route('dosen.profil.index')->with('success', 'Informasi detail berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui informasi detail: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editPrefrensiLokasi()
    {
        $title = 'Edit Preferensi Lokasi';
        $preferensiLokasiDosen = PreferensiLokasiDosen::where('dosen_id', auth()->user()->dosen->id)->get();

        if (request()->ajax() && request()->has('partial')) {
            return response()->json([
                'success' => true,
                'html' => view('partials.preferensi-lokasi-list-dosen', compact('preferensiLokasiDosen'))->render()
            ]);
        }
        return view('profil.dosen.form-preferensi-lokasi', compact('title', 'preferensiLokasiDosen'));
    }

    public function storePreferensiLokasi(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'preferensi_lokasi_id_type' => 'required|in:provinsi,kabupaten,kecamatan,desa',
                'preferensi_lokasi_id' => 'required',
                'preferensi_lokasi_id_input' => 'required|string|max:255',
            ]);

            $preferensiLokasi = new PreferensiLokasiDosen();
            $preferensiLokasi->dosen_id = auth()->user()->dosen->id;
            $preferensiLokasi->nama_tampilan = $validated['preferensi_lokasi_id_input'];
            $preferensiLokasi->negara_id = 1; // Assuming 1 is the ID for Indonesia

            if ($validated['preferensi_lokasi_id_type'] == 'provinsi') {
                $preferensiLokasi->provinsi_id = $validated['preferensi_lokasi_id'];
            } elseif ($validated['preferensi_lokasi_id_type'] == 'kabupaten') {
                $preferensiLokasi->kabupaten_id = $validated['preferensi_lokasi_id'];
                $provinsi_id = KabupatenModel::where('id', $validated['preferensi_lokasi_id'])->first()->provinsi_id;
                $preferensiLokasi->provinsi_id = $provinsi_id;
            } elseif ($validated['preferensi_lokasi_id_type'] == 'kecamatan') {
                $preferensiLokasi->kecamatan_id = $validated['preferensi_lokasi_id'];
                $kabupaten_id = KecamatanModel::where('id', $validated['preferensi_lokasi_id'])->first()->kabupaten_id;
                $preferensiLokasi->kabupaten_id = $kabupaten_id;
                $provinsi_id = KabupatenModel::where('id', $kabupaten_id)->first()->provinsi_id;
                $preferensiLokasi->provinsi_id = $provinsi_id;
            } elseif ($validated['preferensi_lokasi_id_type'] == 'desa') {
                $preferensiLokasi->desa_id = $validated['preferensi_lokasi_id'];
                $kecamatan_id = DesaModel::where('id', $validated['preferensi_lokasi_id'])->first()->kecamatan_id;
                $preferensiLokasi->kecamatan_id = $kecamatan_id;
                $kabupaten_id = KecamatanModel::where('id', $kecamatan_id)->first()->kabupaten_id;
                $preferensiLokasi->kabupaten_id = $kabupaten_id;
                $provinsi_id = KabupatenModel::where('id', $kabupaten_id)->first()->provinsi_id;
                $preferensiLokasi->provinsi_id = $provinsi_id;
            }

            $preferensiLokasi->save();

            $preferensiLokasiDosen = PreferensiLokasiDosen::where('dosen_id', auth()->user()->dosen->id)->get();

            $html = view('partials.preferensi-lokasi-list-dosen', compact('preferensiLokasiDosen'))->render();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Preferensi lokasi berhasil ditambahkan',
                'html' => $html
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan preferensi lokasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyPreferensiLokasi($id)
    {
        try {
            DB::beginTransaction();

            $preferensiLokasi = PreferensiLokasiDosen::findOrFail($id);

            if ($preferensiLokasi->dosen_id !== auth()->user()->dosen->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $preferensiLokasi->delete();

            $preferensiLokasiDosen = PreferensiLokasiDosen::where('dosen_id', auth()->user()->dosen->id)->get();

            $html = view('partials.preferensi-lokasi-list-dosen', compact('preferensiLokasiDosen'))->render();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Preferensi lokasi berhasil dihapus',
                'html' => $html
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus preferensi lokasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editMinat()
    {
        $title = 'Edit Minat';
        $listMinat = BidangKeahlianModel::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'value' => $item->nama
            ];
        })->toArray();
        $listMinatDosen = MinatDosenModel::with('bidangKeahlian')->where('dosen_id', auth()->user()->dosen->id)->get();

        if (request()->ajax() && request()->has('partial')) {
            return response()->json([
                'success' => true,
                'html' => view('partials.minat-list-dosen', compact('listMinatDosen'))->render()
            ]);
        }

        return view('profil.dosen.form-minat', compact('listMinatDosen', 'listMinat', 'title'));
    }

    public function storeMinat(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'minat_id' => 'required|exists:m_bidang_keahlian,id',
            ]);

            $minat = new MinatDosenModel();
            $minat->dosen_id = auth()->user()->dosen->id;
            $minat->bidang_keahlian_id = $validated['minat_id'];
            $minat->save();

            $listMinatDosen = MinatDosenModel::where('dosen_id', auth()->user()->dosen->id)
                ->with('bidangKeahlian')
                ->get();

            $html = view('partials.minat-list-dosen', compact('listMinatDosen'))->render();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Minat berhasil ditambahkan',
                'html' => $html
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan minat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyMinat($id)
    {
        try {
            DB::beginTransaction();

            $minat = MinatDosenModel::findOrFail($id);

            if ($minat->dosen_id !== auth()->user()->dosen->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $minat->delete();

            $listMinatDosen = MinatDosenModel::where('dosen_id', auth()->user()->dosen->id)
                ->with('bidangKeahlian')
                ->get();

            $html = view('partials.minat-list-dosen', compact('listMinatDosen'))->render();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Minat berhasil dihapus',
                'html' => $html
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus minat: ' . $e->getMessage()
            ], 500);
        }
    }
}
