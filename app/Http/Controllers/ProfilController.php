<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\BidangKeahlianModel;
use App\Models\DesaModel;
use App\Models\DokumenUserModel;
use App\Models\JenisDokumenModel;
use App\Models\KabupatenModel;
use App\Models\KeahlianMahasiswaModel;
use App\Models\KeahlianTeknisModel;
use App\Models\KecamatanModel;
use App\Models\MinatDosenModel;
use App\Models\MinatMahasiswaModel;
use App\Models\PreferensiLokasiDosen;
use App\Models\preferensiLokasiDosenModel;
use App\Models\PreferensiLokasiMahasiswa;
use App\Models\ProvinsiModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $title = 'Profil Pengguna';
        $breadcrumb = [
            'title' => $title,
            'list' => [$title],
        ];

        $data = match ($user->level) {
            UserRole::ADMIN => $user->admin,
            UserRole::DOSEN => $user->dosen,
            UserRole::MAHASISWA => $user->mahasiswa,
        };

        return view('profil.index', compact('title', 'breadcrumb', 'data'));
    }

    public function informasiPengguna()
    {
        $user = Auth::user();

        $title = 'Edit Informasi Pengguna';

        $data = match ($user->level) {
            UserRole::ADMIN => $user->admin,
            UserRole::DOSEN => $user->dosen,
            UserRole::MAHASISWA => $user->mahasiswa,
        };

        return view('profil.form-informasi-pengguna', compact('title', 'data'));
    }

    public function informasiPenggunaUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'alamat' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid('profile_') . '_' . time() . '.' . $extension;
            $file->storeAs('public/users/foto_profil', $fileName);
            $user->path_foto_profil = 'users/foto_profil/' . $fileName;
            $user->save();
        }

        $user = match ($user->level) {
            UserRole::ADMIN => $user->admin,
            UserRole::DOSEN => $user->dosen,
            UserRole::MAHASISWA => $user->mahasiswa,
        };

        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->route('profil.index')->with('success', 'Informasi pengguna berhasil diperbarui');
    }

    public function informasiDetail()
    {
        $user = Auth::user();

        $title = 'Edit Informasi Pengguna';

        $data = match ($user->level) {
            UserRole::DOSEN => $user->dosen,
            UserRole::MAHASISWA => $user->mahasiswa,
        };

        return view('profil.form-informasi-detail', compact('title', 'data'));
    }

    public function informasiDetailUpdate(Request $request)
    {
        $user = Auth::user();

        if (has_role(UserRole::DOSEN)) {
            $user = $user->dosen;
            $validated = $request->validate([
                'jenis_kelamin' => 'required|in:L,P',
                'no_telepon' => 'nullable|string|max:20',
            ]);
        } else if (has_role(UserRole::MAHASISWA)) {
            $user = $user->mahasiswa;
            $validated = $request->validate([
                'jenis_kelamin' => 'required|in:L,P',
                'no_telepon' => 'nullable|string|max:20',
                'ipk' => 'numeric|min:0|max:4'
            ]);
        }

        $user->update($validated);

        return redirect()->route('profil.index')->with('success', 'Informasi pengguna berhasil diperbarui');
    }

    public function minat()
    {
        $user = Auth::user();

        $title = 'Edit Minat';
        $listMinat = BidangKeahlianModel::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'value' => $item->nama
            ];
        })->toArray();

        $tag = match ($user->level) {
            UserRole::DOSEN => $user->dosen->minat->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->bidangKeahlian->nama
                ];
            }),
            UserRole::MAHASISWA => $user->mahasiswa->minat->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->bidangKeahlian->nama
                ];
            }),
        };

        $tag->route = 'profil.minat.delete';

        if (request()->ajax() && request()->has('partial')) {
            return response()->json([
                'success' => true,
                'html' => view('partials._tag_cross_delete', compact('tag'))->render()
            ]);
        }

        return view('profil.form-minat', compact('tag', 'listMinat', 'title'));
    }

    public function storeMinat(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'minat_id' => 'required|exists:m_bidang_keahlian,id',
                'minat_id_input' => 'required|string|max:255',
            ]);

            $namaMinat = BidangKeahlianModel::find($validated['minat_id'])->nama;
            if ($namaMinat !== $validated['minat_id_input']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Nama minat tidak sesuai dengan yang dipilih.'
                ]);
            }

            $user = Auth::user();

            $exists = match ($user->level) {
                UserRole::DOSEN => MinatDosenModel::where([
                    'dosen_id' => $user->dosen->id,
                    'bidang_keahlian_id' => $validated['minat_id']
                ])->exists(),
                UserRole::MAHASISWA => MinatMahasiswaModel::where([
                    'mahasiswa_id' => $user->mahasiswa->id,
                    'bidang_keahlian_id' => $validated['minat_id']
                ])->exists(),
            };

            if ($exists) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Minat sudah ada.'
                ]);
            }

            match ($user->level) {
                UserRole::DOSEN => MinatDosenModel::firstOrCreate(
                    [
                        'dosen_id' => $user->dosen->id,
                        'bidang_keahlian_id' => $validated['minat_id']
                    ]
                ),
                UserRole::MAHASISWA => MinatMahasiswaModel::firstOrCreate(
                    [
                        'mahasiswa_id' => $user->mahasiswa->id,
                        'bidang_keahlian_id' => $validated['minat_id']
                    ]
                )
            };
            DB::commit();

            return $this->partialMinatReload($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan minat.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyMinat($id)
    {
        $user = Auth::user();

        match ($user->level) {
            UserRole::DOSEN => MinatDosenModel::destroy($id),
            UserRole::MAHASISWA => MinatMahasiswaModel::destroy($id),
        };

        return $this->partialMinatReload($user);
    }

    public function partialMinatReload($user)
    {
        $tag = match ($user->level) {
            UserRole::DOSEN => $user->dosen->minat->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->bidangKeahlian->nama
                ];
            }),
            UserRole::MAHASISWA => $user->mahasiswa->minat->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->bidangKeahlian->nama
                ];
            }),
        };

        $tag->route = 'profil.minat.delete';

        return response()->json([
            'success' => true,
            'html' => view('partials._tag_cross_delete', compact('tag'))->render()
        ]);
    }

    public function preferensiLokasi()
    {
        $user = Auth::user();

        $title = 'Edit Preferensi Lokasi';

        $tag = match ($user->level) {
            UserRole::DOSEN => $user->dosen->preferensiLokasi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->nama_tampilan
                ];
            }),
            UserRole::MAHASISWA => $user->mahasiswa->preferensiLokasi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->nama_tampilan
                ];
            }),
        };

        $tag->route = 'profil.preferensi-lokasi.delete';

        if (request()->ajax() && request()->has('partial')) {
            return response()->json([
                'success' => true,
                'html' => view('partials._tag_cross_delete', compact('tag'))->render()
            ]);
        }

        return view('profil.form-preferensi-lokasi', compact('tag', 'title'));
    }

    public function storePreferensiLokasi(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $validated = $request->validate([
                'preferensi_lokasi_id_type' => 'required|in:provinsi,kabupaten,kecamatan,desa',
                'preferensi_lokasi_id' => 'required',
                'preferensi_lokasi_id_input' => 'required|string|max:255',
            ]);

            // Cek duplikasi preferensi lokasi
            $model = null;
            $where = [];
            if ($user->level == UserRole::DOSEN) {
                $model = PreferensiLokasiDosenModel::class;
                $where['dosen_id'] = $user->dosen->id;
            } elseif ($user->level == UserRole::MAHASISWA) {
                $model = PreferensiLokasiMahasiswa::class;
                $where['mahasiswa_id'] = $user->mahasiswa->id;
            }

            // Set kolom id lokasi sesuai tipe
            $idType = $validated['preferensi_lokasi_id_type'];
            $idField = $idType . '_id';
            $where[$idField] = $validated['preferensi_lokasi_id'];

            if ($user->level == UserRole::DOSEN) {
                $preferensiLokasi = new PreferensiLokasiDosenModel();
                $preferensiLokasi->dosen_id = $user->dosen->id;
            } elseif ($user->level == UserRole::MAHASISWA) {
                $preferensiLokasi = new PreferensiLokasiMahasiswa();
                $preferensiLokasi->mahasiswa_id = $user->mahasiswa->id;
            }

            $preferensiLokasi->nama_tampilan = $validated['preferensi_lokasi_id_input'];
            $preferensiLokasi->negara_id = 1;

            $fullName = '';
            if ($idType == 'provinsi') {
                $preferensiLokasi->provinsi_id = $validated['preferensi_lokasi_id'];
                $provinsi = ProvinsiModel::find($preferensiLokasi->provinsi_id);
                $preferensiLokasi->longitude = $provinsi->longitude;
                $preferensiLokasi->latitude = $provinsi->latitude;
                $fullName = $provinsi->nama;
            } elseif ($idType == 'kabupaten') {
                $kabupaten = KabupatenModel::find($validated['preferensi_lokasi_id']);
                $preferensiLokasi->longitude = $kabupaten->longitude;
                $preferensiLokasi->latitude = $kabupaten->latitude;
                $preferensiLokasi->kabupaten_id = $validated['preferensi_lokasi_id'];
                $provinsi_id = $kabupaten->provinsi_id;
                $preferensiLokasi->provinsi_id = $provinsi_id;
                $fullName = $kabupaten->nama . ', ' . ProvinsiModel::find($provinsi_id)->nama;
            } elseif ($idType == 'kecamatan') {
                $kecamatan = KecamatanModel::find($validated['preferensi_lokasi_id']);
                $preferensiLokasi->longitude = $kecamatan->longitude;
                $preferensiLokasi->latitude = $kecamatan->latitude;
                $preferensiLokasi->kecamatan_id = $validated['preferensi_lokasi_id'];
                $kabupaten_id = $kecamatan->kabupaten_id;
                $preferensiLokasi->kabupaten_id = $kabupaten_id;
                $provinsi_id = KabupatenModel::where('id', $kabupaten_id)->first()->provinsi_id;
                $preferensiLokasi->provinsi_id = $provinsi_id;
                $fullName = $kecamatan->nama . ', ' . KabupatenModel::find($kabupaten_id)->nama . ', ' . ProvinsiModel::find($provinsi_id)->nama;
            } elseif ($idType == 'desa') {
                $preferensiLokasi->desa_id = $validated['preferensi_lokasi_id'];
                $kecamatan_id = DesaModel::where('id', $validated['preferensi_lokasi_id'])->first()->kecamatan_id;
                $kecamatan = KecamatanModel::find($kecamatan_id);
                $preferensiLokasi->longitude = $kecamatan->longitude;
                $preferensiLokasi->latitude = $kecamatan->latitude;
                $preferensiLokasi->kecamatan_id = $kecamatan_id;
                $kabupaten_id = $kecamatan->kabupaten_id;
                $preferensiLokasi->kabupaten_id = $kabupaten_id;
                $provinsi_id = KabupatenModel::where('id', $kabupaten_id)->first()->provinsi_id;
                $preferensiLokasi->provinsi_id = $provinsi_id;
                $fullName = DesaModel::find($validated['preferensi_lokasi_id'])->nama . ', ' . KecamatanModel::find($kecamatan_id)->nama . ', ' . KabupatenModel::find($kabupaten_id)->nama . ', ' . ProvinsiModel::find($provinsi_id)->nama;
            }

            if ($fullName !== $validated['preferensi_lokasi_id_input']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Nama tampilan lokasi tidak sesuai dengan lokasi yang dipilih.'
                ]);
            }

            // Jika sudah ada, tolak
            if ($model && $model::where($where)->exists()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Preferensi lokasi sudah ada.'
                ]);
            }

            $preferensiLokasi->save();

            DB::commit();
            return $this->partialPrefrensiLokasiReload($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan preferensi lokasi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyPreferensiLokasi($id)
    {
        $user = Auth::user();

        match ($user->level) {
            UserRole::DOSEN => PreferensiLokasiDosen::destroy($id),
            UserRole::MAHASISWA => PreferensiLokasiMahasiswa::destroy($id),
        };

        return $this->partialPrefrensiLokasiReload($user);
    }

    public function partialPrefrensiLokasiReload($user)
    {
        $tag = match ($user->level) {
            UserRole::DOSEN => $user->dosen->preferensiLokasi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->nama_tampilan
                ];
            }),
            UserRole::MAHASISWA => $user->mahasiswa->preferensiLokasi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->nama_tampilan
                ];
            }),
        };

        $tag->route = 'profil.preferensi-lokasi.delete';

        return response()->json([
            'success' => true,
            'html' => view('partials._tag_cross_delete', compact('tag'))->render()
        ]);
    }

    public function keahlian()
    {
        $user = Auth::user();

        $title = 'Edit Keahlian';

        $listKeahlain = KeahlianTeknisModel::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'value' => $item->nama
            ];
        })->toArray();

        $tag = match ($user->level) {
            UserRole::MAHASISWA => $user->mahasiswa->keahlian->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->getKeahlianTeknisNameAttribute()
                ];
            })
        };

        $tag->route = 'profil.keahlian.delete';

        if (request()->ajax() && request()->has('partial')) {
            return response()->json([
                'success' => true,
                'html' => view('partials._tag_cross_delete', compact('tag'))->render()
            ]);
        }

        return view('profil.form-keahlian', compact('tag', 'title', 'listKeahlain'));
    }

    public function storeKeahlian(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'keahlian_id' => 'required|exists:m_keahlian_teknis,id',
                'level_id' => 'required|in:1,2,3',
                'keahlian_id_input' => 'required|string|max:255',
            ]);

            $namaKeahlian = KeahlianTeknisModel::find($validated['keahlian_id'])->nama;
            if ($namaKeahlian !== $validated['keahlian_id_input']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Nama keahlian tidak sesuai dengan yang dipilih.'
                ]);
            }

            $user = Auth::user();

            match ($user->level) {
                UserRole::MAHASISWA => KeahlianMahasiswaModel::updateOrCreate(
                    [
                        'mahasiswa_id' => $user->mahasiswa->id,
                        'keahlian_teknis_id' => $validated['keahlian_id'],
                    ],
                    [
                        'level' => $validated['level_id']
                    ]
                )
            };

            DB::commit();
            return $this->partialKeahlianReload($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan keahlian.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyKeahlian($id)
    {
        $user = Auth::user();

        match ($user->level) {
            UserRole::MAHASISWA => KeahlianMahasiswaModel::destroy($id),
        };

        return $this->partialKeahlianReload($user);
    }

    public function partialKeahlianReload($user)
    {
        $tag = match ($user->level) {
            UserRole::MAHASISWA => $user->mahasiswa->keahlian->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->getKeahlianTeknisNameAttribute()
                ];
            })
        };

        $tag->route = 'profil.keahlian.delete';

        return response()->json([
            'success' => true,
            'html' => view('partials._tag_cross_delete', compact('tag'))->render()
        ]);
    }

    public function tambahDokumen()
    {
        $title = "Tambah Dokumen";
        $dokumenTambahan = JenisDokumenModel::where('default', 0)->get();

        return view('profil.form-dokumen', compact('title', 'dokumenTambahan'));
    }

    public function storeDokumen(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx|max:5000',
            'jenis_dokumen_id' => 'required|exists:m_jenis_dokumen,id',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '_' . time() . '.' . $extension;
            $file->storeAs('public/users/dokumen', $fileName);
        }

        DokumenUserModel::create([
            'user_id' => auth()->user()->id,
            'jenis_dokumen_id' => $request->input('jenis_dokumen_id'),
            'label' => $request->input('label'),
            'nama' => $fileName,
            'path' => 'users/dokumen/'
        ]);

        return redirect()->route('profil.index')->with('success', 'Dokumen berhasil diunggah');
    }
}
