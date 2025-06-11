<?php

namespace App\Http\Controllers;

use App\Constants\RegexPatterns;
use App\Enums\UserRole;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\ProgramStudiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ManajemenPenggunaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('level') && in_array(strtoupper($request->level), ['ADMIN', 'DOSEN', 'MAHASISWA'])) {
            $level = $request->get('level');
        } else {
            abort(404, 'Level pengguna tidak ditentukan.');
        }

        if ($request->ajax()) {
            $data = UserModel::query()
                ->with(['admin', 'dosen', 'mahasiswa'])
                ->where('level', $level);

            return DataTables::of($data)
                ->editColumn('username', fn($data) => $data->username)
                ->editColumn('nama', fn($data) => $data->getNama())
                ->addColumn('status', function ($row) use ($level) {
                    if (strtoupper($level) === 'MAHASISWA' && $row->mahasiswa) {
                        return $row->mahasiswa->status ?? '-';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.manajemen-pengguna.edit', $row->id);
                    $deleteUrl = route('admin.manajemen-pengguna.delete', $row->id);
                    return view('components.action-buttons', compact('editUrl', 'deleteUrl'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Manajemen Pengguna' . ($level ? ' - ' . $level : '');
        $breadcrumb = [
            'title' => 'Manajemen Pengguna',
            'list' => ['Manajemen Pengguna', $level ? ucfirst($level) : '']
        ];

        return view('admin.manajemen_pengguna.index', compact('title', 'breadcrumb', 'level'));
    }

    public function create(Request $request)
    {
        if ($request->has('level') && in_array(strtoupper($request->level), ['ADMIN', 'DOSEN', 'MAHASISWA'])) {
            $level = $request->get('level');
        } else {
            abort(404, 'Level pengguna tidak ditentukan.');
        }
        $title = 'Pengguna';
        $programStudi = ProgramStudiModel::all();
        $breadcrumb = [
            'title' => 'Pengguna',
            'list' => ['Pengguna', ucfirst($level), 'Tambah']
        ];
        return view('admin.manajemen_pengguna.form', compact('title', 'breadcrumb', 'programStudi', 'level'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => ['required', Rule::in(['ADMIN', 'DOSEN', 'MAHASISWA'])],
        ]);

        switch ($validated['level']) {
            case 'ADMIN':
                $validated = array_merge($validated, $request->validate([
                    'username' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:m_user,username',
                    'nama' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|string|max:100',
                ]));
                break;

            case 'DOSEN':
                $validated = array_merge($validated, $request->validate([
                    'nip' => 'required|unique:m_dosen,nip|regex:/^[0-9]+$/|max:25',
                    'nama' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|string|max:100',
                ]));
                $validated['username'] = $request->input('nip');
                break;

            case 'MAHASISWA':
                $validated = array_merge($validated, $request->validate([
                    'nim' => 'required|regex:/^[0-9]+$/|max:15|unique:m_mahasiswa,nim',
                    'nama' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|string|max:100',
                    'program_studi' => 'required|exists:m_program_studi,id',
                    'angkatan' => 'required|regex:/^[0-9]+$/|max:4',
                    'status' => 'required|in:aktif,nonaktif',
                ]));
                $validated['username'] = $request->input('nim');
                break;
        }

        $validated['password'] = bcrypt($validated['username']);

        try {
            DB::beginTransaction();

            $user = UserModel::create([
                'username' => $validated['username'],
                'password' => $validated['password'],
                'level' => $validated['level'],
            ]);

            switch ($validated['level']) {
                case 'ADMIN':
                    AdminModel::create([
                        'user_id' => $user->id,
                        'nama' => $validated['nama'],
                    ]);
                    break;

                case 'DOSEN':
                    DosenModel::create([
                        'user_id' => $user->id,
                        'nip' => $validated['nip'],
                        'nama' => $validated['nama'],
                    ]);
                    break;

                case 'MAHASISWA':
                    MahasiswaModel::create([
                        'user_id' => $user->id,
                        'nim' => $validated['nim'],
                        'nama' => $validated['nama'],
                        'program_studi_id' => $validated['program_studi'],
                        'angkatan' => $validated['angkatan'],
                    ]);
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil ditambahkan.',
                'redirect' => route('admin.manajemen-pengguna.index', ['level' => $validated['level']])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan pengguna.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function edit(string $id)
    {
        $data = UserModel::with(['admin', 'dosen', 'mahasiswa'])->findOrFail($id);
        $programStudi = ProgramStudiModel::all();
        $title = 'Manajemen Pengguna';
        $breadcrumb = [
            'title' => 'Manajemen Pengguna',
            'list' => ['Manajemen Pengguna', ucfirst($data->level->value), 'Edit']
        ];

        return view('admin.manajemen_pengguna.form', compact('data', 'title', 'breadcrumb', 'programStudi'));
    }

    public function update(Request $request, string $id)
    {
        $user = UserModel::findOrFail($id);
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak ditemukan.'], 404);
        }

        $data = null;

        switch ($user->level) {
            case UserRole::ADMIN:
                $validated = $request->validate([
                    'nama' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|string|max:100',
                ]);
                $data = AdminModel::where('user_id', $id)->firstOrFail();
                break;

            case UserRole::DOSEN:
                $validated = $request->validate([
                    'nama' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|string|max:100',
                ]);
                $data = DosenModel::where('user_id', $id)->firstOrFail();
                break;

            case UserRole::MAHASISWA:
                $validated = $request->validate([
                    'nama' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|string|max:100',
                    'program_studi' => 'required|exists:m_program_studi,id',
                    'angkatan' => 'required|string|max:4',
                    'status' => 'required|in:aktif,nonaktif',
                ]);
                $data = MahasiswaModel::where('user_id', $id)->firstOrFail();

                $validated = [
                    'nama' => $validated['nama'],
                    'program_studi_id' => $validated['program_studi'],
                    'angkatan' => $validated['angkatan'],
                    'status' => $validated['status'],
                ];
                break;
        }

        try {
            DB::beginTransaction();

            $data->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil diperbarui.',
                'redirect' => route('admin.manajemen-pengguna.index', ['level' => strtolower($user->level->value)])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pengguna.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $user = UserModel::findOrFail($id);
            $user->delete();

            switch ($user->level) {
                case UserRole::ADMIN:
                    AdminModel::where('user_id', $id)->delete();
                    break;

                case UserRole::DOSEN:
                    DosenModel::where('user_id', $id)->delete();
                    break;

                case UserRole::MAHASISWA:
                    MahasiswaModel::where('user_id', $id)->delete();
                    break;
            }

            DB::commit();

            return response()->json(['success' => 'Data berhasil dihapus.']);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('User deletion failed: ' . $e->getMessage());

            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    public function importIndex(Request $request)
    {
        $level = $request->get('level');
        $title = 'Impor Pengguna';
        $breadcrumb = [
            'title' => 'Impor Pengguna',
            'list' => ['Impor Pengguna']
        ];
        return view('admin.manajemen_pengguna.import', compact('title', 'breadcrumb', 'level'));
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'level' => ['required', Rule::in(['ADMIN', 'DOSEN', 'MAHASISWA'])],
            'file_import' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $file = $request->file('file_import');
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getRealPath());
            $spreadsheet = $reader->load($file->getRealPath());
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $insertData = [];
            if (count($sheetData) > 1) {
                $header = array_map('strtolower', array_shift($sheetData));
                if ($validated['level'] === 'ADMIN' && $header !== ['username', 'nama']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Format file tidak sesuai untuk level ADMIN.'
                    ]);
                } elseif ($validated['level'] === 'DOSEN' && $header !== ['nip', 'nama']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Format file tidak sesuai untuk level DOSEN.'
                    ]);
                } elseif ($validated['level'] === 'MAHASISWA' && $header !== ['nim', 'nama', 'kode_program_studi', 'angkatan', 'status']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Format file tidak sesuai untuk level MAHASISWA.'
                    ]);
                }
                foreach ($sheetData as $index => $row) {
                    if (count($row) < 2) continue;
                    $user = UserModel::where('username', $row[0])->first();
                    if ($user) {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => ($validated['level'] === 'ADMIN' ? 'Username ' : ($validated['level'] === 'DOSEN' ? 'NIP ' : 'NIM ')) . $row[0] . ' sudah digunakan pada baris: ' . ($index + 2),]);
                    }
                    $user = UserModel::create([
                        'username' => $row[0],
                        'password' => bcrypt($row[0]),
                        'level' => $validated['level'],
                    ]);
                    switch ($validated['level']) {
                        case 'ADMIN':
                            $rules = [
                                'nama' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|string|max:100',
                            ];
                            $validatedRow = Validator::make(['nama' => $row[1]], $rules);
                            if ($validatedRow->fails()) {
                                DB::rollBack();
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Data tidak valid pada baris ' . ($index + 2),
                                    'errors' => $validatedRow->errors()
                                ]);
                            }
                            $insertData[] = [
                                'user_id' => $user->id,
                                'nama' => $row[1],
                            ];
                            break;

                        case 'DOSEN':
                            $rules = [
                                'nip' => 'required|regex:/^[0-9]+$/|max:25|unique:m_dosen,nip',
                                'nama' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|string|max:100',
                            ];
                            $validatedRow = Validator::make([
                                'nip' => $row[0],
                                'nama' => $row[1]
                            ], $rules);
                            if ($validatedRow->fails()) {
                                DB::rollBack();
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Data tidak valid pada baris ' . ($index + 2),
                                    'errors' => $validatedRow->errors()
                                ]);
                            }
                            $insertData[] = [
                                'user_id' => $user->id,
                                'nip' => $row[0],
                                'nama' => $row[1],
                            ];
                            break;

                        case 'MAHASISWA':
                            $rules = [
                                'nim' => 'required|regex:/^[0-9]+$/|max:15|unique:m_mahasiswa,nim',
                                'nama' => 'required|regex:' . RegexPatterns::SAFE_INPUT . '|string|max:100',
                                'kode_program_studi' => 'required|exists:m_program_studi,kode',
                                'angkatan' => 'required|regex:/^[0-9]+$/|max:4',
                                'status' => 'required|in:aktif,nonaktif',
                            ];
                            $validatedRow = Validator::make([
                                'nim' => $row[0],
                                'nama' => $row[1],
                                'kode_program_studi' => $row[2],
                                'angkatan' => $row[3],
                                'status' => $row[4],
                            ], $rules);
                            if ($validatedRow->fails()) {
                                DB::rollBack();
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Data tidak valid pada baris ' . ($index + 2),
                                    'errors' => $validatedRow->errors()
                                ]);
                            }
                            $insertData[] = [
                                'user_id' => $user->id,
                                'nim' => $row[0],
                                'nama' => $row[1],
                                'program_studi_id' => ProgramStudiModel::where('kode', $row[2])->value('id'),
                                'angkatan' => $row[3],
                                'status' => $row[4],
                            ];
                            break;

                        default:
                            DB::rollBack();
                            return response()->json([
                                'status' => false,
                                'message' => 'Level pengguna tidak valid.'
                            ]);
                    }
                }
                if (count($insertData) > 0) {
                    switch ($validated['level']) {
                        case 'ADMIN':
                            AdminModel::insertOrIgnore($insertData);
                            break;

                        case 'DOSEN':
                            DosenModel::insertOrIgnore($insertData);
                            break;

                        case 'MAHASISWA':
                            MahasiswaModel::insertOrIgnore($insertData);
                            break;
                    }
                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimpor.',
                    ]);
                }
            }
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang ditemukan di file yang diunggah.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengimpor data.',
                'errors' => $e->getMessage()
            ]);
        }
    }
}
