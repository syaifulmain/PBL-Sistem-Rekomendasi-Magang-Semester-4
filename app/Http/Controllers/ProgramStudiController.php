<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ProgramStudiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProgramStudiModel::query();

            return DataTables::of($data)
                ->editColumn('jenjang', fn($row) => ucfirst($row->jenjang))
                ->addColumn('action', function ($row) {
                    $deleteUrl = route('admin.program-studi.delete', $row->id);
                    $editUrlModal = route('admin.program-studi.edit', $row->id);
                    return view('components.action-buttons', compact('deleteUrl', 'editUrlModal'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Manajemen Program Studi';
        $breadcrumb = [
            'title' => 'Program Studi',
            'list' => ['Program Studi']
        ];

        return view('admin.program-studi.index', compact('title', 'breadcrumb'));
    }

    public function create()
    {
        $title = 'Manajemen Program Studi';
        $breadcrumb = [
            'title' => 'Program Studi',
            'list' => ['Program Studi', 'Create']
        ];

        return view('admin.program-studi.form', compact('title', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|max:5|unique:m_program_studi,kode',
            'nama' => 'required|max:100',
            'jenjang' => 'required|in:D3,D4',
        ]);

        DB::beginTransaction();
        try {
            ProgramStudiModel::create($validated);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Program Studi berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan Program Studi.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $prodi = ProgramStudiModel::findOrFail($id);

        $title = 'Manajemen Program Studi';
        $breadcrumb = [
            'title' => 'Program Studi',
            'list' => ['Program Studi', 'Edit']
        ];

        return view('admin.program-studi.form', compact('prodi', 'title', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => [
                'required',
                'max:5',
                Rule::unique('m_program_studi')->ignore($id),
            ],
            'nama' => 'required|max:100',
            'jenjang' => 'required|in:D3,D4',
        ]);

        DB::beginTransaction();
        try {
            $prodi = ProgramStudiModel::findOrFail($id);
            $prodi->kode = $request->kode;
            $prodi->nama = $request->nama;
            $prodi->jenjang = $request->jenjang;
            $prodi->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Program Studi berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui Program Studi.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $programStudi = ProgramStudiModel::findOrFail($id);
            $programStudi->delete();
            DB::commit();

            return response()->json(['success' => 'Data berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }
}
