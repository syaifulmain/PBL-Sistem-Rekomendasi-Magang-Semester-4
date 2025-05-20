<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\AdminModel;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\ProgramStudiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class ManajemenPenggunaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = UserModel::with(['admin', 'dosen', 'mahasiswa']);

            if ($request->has('level') && $request->level != '') {
                $query->where('level', $request->level);
            }

            $userData = $query->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'username' => $user->username,
                        'nama' => $user->getNama(),
                        'level' => $user->level->value,
                    ];
                });

            return DataTables::of($userData)
                ->editColumn('username', function ($row) {
                    return $row['username'];
                })
                ->editColumn('nama', function ($row) {
                    return $row['nama'];
                })
                ->editColumn('level', function ($row) {
                    return $row['level'];
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.manajemen-pengguna.edit', $row['id']);
                    $deleteUrl = route('admin.manajemen-pengguna.delete', $row['id']);

                    return '
                        <a href="' . $editUrl . '" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm btn-delete" data-url="' . $deleteUrl . '">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Manajemen Pengguna';
        $breadcrumb = [
            'title' => 'Manajemen Pengguna',
            'list' => ['Manajemen Pengguna']
        ];
        return view('admin.manajemen_pengguna.index', compact('title', 'breadcrumb'));
    }

    public function create()
    {
        $title = 'Pengguna';
        $programStudi = ProgramStudiModel::all();
        $breadcrumb = [
            'title' => 'Pengguna',
            'list' => ['Pengguna', 'Create']
        ];
        return view('admin.manajemen_pengguna.form', compact('title', 'breadcrumb', 'programStudi'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'level' => ['required', Rule::in(['ADMIN', 'DOSEN', 'MAHASISWA'])],
            ]);

            switch ($validated['level']) {
                case 'ADMIN':
                    $validated = array_merge($validated, $request->validate([
                        'username' => 'required|unique:m_user,username',
                        'nama' => 'required|string|max:100',
                    ]));
                    break;

                case 'DOSEN':
                    $validated = array_merge($validated, $request->validate([
                        'nip' => 'required|unique:m_dosen,nip',
                        'nama' => 'required|string|max:100',
                    ]));
                    $validated['username'] = $request->input('nip');
                    break;

                case 'MAHASISWA':
                    $validated = array_merge($validated, $request->validate([
                        'nim' => 'required|string|max:15|unique:m_mahasiswa,nim',
                        'nama' => 'required|string|max:100',
                        'program_studi' => 'required|exists:m_program_studi,id',
                        'angkatan' => 'required|string|max:4',
                    ]));
                    $validated['username'] = $request->input('nim');
                    break;
            }

            $validated['password'] = bcrypt('12345678');

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

            return redirect()
                ->route('admin.manajemen-pengguna.index')
                ->with('success', 'Pengguna berhasil ditambahkan.');

        } catch (ValidationException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('User creation failed: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(string $id)
    {
        $data = UserModel::with(['admin', 'dosen', 'mahasiswa'])->findOrFail($id);
        $programStudi = ProgramStudiModel::all();
        $title = 'Manajemen Pengguna';
        $breadcrumb = [
            'title' => 'Manajemen Pengguna',
            'list' => ['Manajemen Pengguna', 'Edit']
        ];

        return view('admin.manajemen_pengguna.form', compact('data', 'title', 'breadcrumb', 'programStudi'));
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $user = UserModel::findOrFail($id);
            $data = null;

            switch ($user->level) {
                case UserRole::ADMIN:
                    $validated = $request->validate([
                        'nama' => 'required|string|max:100',
                    ]);
                    $data = AdminModel::where('user_id', $id)->firstOrFail();
                    break;

                case UserRole::DOSEN:
                    $validated = $request->validate([
                        'nama' => 'required|string|max:100',
                    ]);
                    $data = DosenModel::where('user_id', $id)->firstOrFail();
                    break;

                case UserRole::MAHASISWA:
                    $validated = $request->validate([
                        'nama' => 'required|string|max:100',
                        'program_studi' => 'required|exists:m_program_studi,id',
                        'angkatan' => 'required|string|max:4',
                    ]);
                    $data = MahasiswaModel::where('user_id', $id)->firstOrFail();

                    $validated = [
                        'nama' => $validated['nama'],
                        'program_studi_id' => $validated['program_studi'],
                        'angkatan' => $validated['angkatan'],
                    ];
                    break;
            }

            $data->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.manajemen-pengguna.index')
                ->with('success', 'Pengguna berhasil diperbarui.');

        } catch (ValidationException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('User update failed: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage())
                ->withInput();
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
}
