<?php

namespace App\Http\Controllers;

use App\Models\BidangKeahlianModel;
use App\Models\DesaModel;
use App\Models\KabupatenModel;
use App\Models\KeahlianMahasiswaModel;
use App\Models\KeahlianTeknisModel;
use App\Models\KecamatanModel;
use App\Models\MahasiswaModel;
use App\Models\MinatMahasiswaModel;
use App\Models\PreferensiLokasiMahasiswa;
use App\Models\ProvinsiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilMahasiswaController extends Controller
{
    public function index()
    {
        $data = MahasiswaModel::with(
            'programStudi',
            'preferensiLokasiMahasiswa',
            'minatMahasiswa.bidangKeahlian',
            'keahlianMahasiswa.keahlianTeknis'
        )->where('id', auth()->user()->mahasiswa->id)->first();
        $title = 'Profil Pengguna';
        $breadcrumb = [
            'title' => 'Profil Pengguna',
            'list' => ['Profil Pengguna']
        ];
        return view('profil.mahasiswa.index', compact('title', 'breadcrumb', 'data'));
    }

    public function edit()
    {
        return view('profil.mahasiswa.edit');
    }

    public function editInformasiPengguna()
    {
        $title = 'Edit Informasi Pengguna';
        $informasiPengguna = MahasiswaModel::with('programStudi', 'user')->where('id', auth()->user()->mahasiswa->id)->first();
        return view('profil.mahasiswa.form-informasi-pengguna', compact('title', 'informasiPengguna'));
    }

    public function updateInformasiPengguna(Request $request)
    {
        try {
            DB::beginTransaction();

            $mahasiswa = MahasiswaModel::where('id', auth()->user()->mahasiswa->id)->first();
            $request->validate([
                'alamat' => 'nullable|string|max:255',
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $mahasiswa->alamat = $request->alamat;

            if ($request->hasFile('foto_profil')) {
                $file = $request->file('foto_profil');
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid('profile_') . '_' . time() . '.' . $extension;
                $file->storeAs('public/foto_profil', $fileName);
                $mahasiswa->user->path_foto_profil = 'foto_profil/' . $fileName;
                $mahasiswa->user->save();
            }

            $mahasiswa->save();

            DB::commit();

            return redirect()->route('mahasiswa.profil.index')->with('success', 'Informasi pengguna berhasil diperbarui');
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
        $informasiDetail = MahasiswaModel::where('id', auth()->user()->mahasiswa->id)->first();
        return view('profil.mahasiswa.form-informasi-detail', compact('title', 'informasiDetail'));
    }

    public function updateInformasiDetail(Request $request)
    {
        try {
            DB::beginTransaction();

            $mahasiswa = MahasiswaModel::where('id', auth()->user()->mahasiswa->id)->first();
            $request->validate([
                'no_telepon' => 'required|string|max:15',
                'ipk' => 'required|numeric|min:0|max:4'
            ]);

            $mahasiswa->no_telepon = $request->no_telepon;
            $mahasiswa->ipk = $request->ipk;
            $mahasiswa->save();

            DB::commit();

            return redirect()->route('mahasiswa.profil.index')->with('success', 'Informasi detail berhasil diperbarui');
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
        $preferensiLokasiMahasiswa = PreferensiLokasiMahasiswa::where('mahasiswa_id', auth()->user()->mahasiswa->id)->get();

        if (request()->ajax() && request()->has('partial')) {
            return response()->json([
                'success' => true,
                'html' => view('partials.preferensi-lokasi-list-mahasiswa', compact('preferensiLokasiMahasiswa'))->render()
            ]);
        }
        return view('profil.mahasiswa.form-preferensi-lokasi', compact('title', 'preferensiLokasiMahasiswa'));
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

            $preferensiLokasi = new PreferensiLokasiMahasiswa();
            $preferensiLokasi->mahasiswa_id = auth()->user()->mahasiswa->id;
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

            DB::commit();

            $preferensiLokasiMahasiswa = PreferensiLokasiMahasiswa::where('mahasiswa_id', auth()->user()->mahasiswa->id)->get();

            $html = view('partials.preferensi-lokasi-list-mahasiswa', compact('preferensiLokasiMahasiswa'))->render();
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

            $preferensiLokasi = PreferensiLokasiMahasiswa::findOrFail($id);

            if ($preferensiLokasi->mahasiswa_id !== auth()->user()->mahasiswa->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $preferensiLokasi->delete();

            $preferensiLokasiMahasiswa = PreferensiLokasiMahasiswa::where('mahasiswa_id', auth()->user()->mahasiswa->id)->get();

            $html = view('partials.preferensi-lokasi-list-mahasiswa', compact('preferensiLokasiMahasiswa'))->render();

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

    public function editKeahlian()
    {
        $title = 'Edit Keahlian';
        $listKeahlain = KeahlianTeknisModel::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'value' => $item->nama
            ];
        })->toArray();
        $listKeahlianMahasiswa = KeahlianMahasiswaModel::with('keahlianTeknis')->where('mahasiswa_id', auth()->user()->mahasiswa->id)->get();

        if (request()->ajax() && request()->has('partial')) {
            return response()->json([
                'success' => true,
                'html' => view('partials.keahlian-list-mahasiswa', compact('listKeahlianMahasiswa'))->render()
            ]);
        }

        return view('profil.mahasiswa.form-keahlian', compact('title', 'listKeahlain', 'listKeahlianMahasiswa'));
    }

    public function storeKeahlian(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'keahlian_id' => 'required|exists:m_keahlian_teknis,id',
                'level_id' => 'required|in:1,2,3',
            ]);

            $keahlian = new KeahlianMahasiswaModel();
            $keahlian->mahasiswa_id = auth()->user()->mahasiswa->id;
            $keahlian->keahlian_teknis_id = $validated['keahlian_id'];
            $keahlian->level = $validated['level_id'];
            $keahlian->save();

            $listKeahlianMahasiswa = KeahlianMahasiswaModel::where('mahasiswa_id', auth()->user()->mahasiswa->id)
                ->with('keahlianTeknis')
                ->get();

            $html = view('partials.keahlian-list-mahasiswa', compact('listKeahlianMahasiswa'))->render();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Keahlian berhasil ditambahkan',
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
                'message' => 'Gagal menambahkan keahlian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyKeahlian($id)
    {
        try {
            DB::beginTransaction();

            $keahlian = KeahlianMahasiswaModel::findOrFail($id);

            if ($keahlian->mahasiswa_id !== auth()->user()->mahasiswa->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $keahlian->delete();

            $listKeahlianMahasiswa = KeahlianMahasiswaModel::where('mahasiswa_id', auth()->user()->mahasiswa->id)
                ->with('keahlianTeknis')
                ->get();

            $html = view('partials.keahlian-list-mahasiswa', compact('listKeahlianMahasiswa'))->render();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Keahlian berhasil dihapus',
                'html' => $html
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus keahlian: ' . $e->getMessage()
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
        $listMinatMahasiswa = MinatMahasiswaModel::with('bidangKeahlian')->where('mahasiswa_id', auth()->user()->mahasiswa->id)->get();

        if (request()->ajax() && request()->has('partial')) {
            return response()->json([
                'success' => true,
                'html' => view('partials.minat-list-mahasiswa', compact('listMinatMahasiswa'))->render()
            ]);
        }

        return view('profil.mahasiswa.form-minat', compact('listMinatMahasiswa', 'listMinat', 'title'));
    }

    public function storeMinat(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'minat_id' => 'required|exists:m_bidang_keahlian,id',
            ]);

            $minat = new MinatMahasiswaModel();
            $minat->mahasiswa_id = auth()->user()->mahasiswa->id;
            $minat->bidang_keahlian_id = $validated['minat_id'];
            $minat->save();

            $listMinatMahasiswa = MinatMahasiswaModel::where('mahasiswa_id', auth()->user()->mahasiswa->id)
                ->with('bidangKeahlian')
                ->get();

            $html = view('partials.minat-list-mahasiswa', compact('listMinatMahasiswa'))->render();

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

            $minat = MinatMahasiswaModel::findOrFail($id);

            if ($minat->mahasiswa_id !== auth()->user()->mahasiswa->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $minat->delete();

            $listMinatMahasiswa = MinatMahasiswaModel::where('mahasiswa_id', auth()->user()->mahasiswa->id)
                ->with('bidangKeahlian')
                ->get();

            $html = view('partials.minat-list-mahasiswa', compact('listMinatMahasiswa'))->render();

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
