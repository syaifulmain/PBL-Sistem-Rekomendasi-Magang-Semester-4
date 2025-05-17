<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudiModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProgramStudiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProgramStudiModel::query();

            return DataTables::of($data)
                ->editColumn('jenjang', fn($row) => ucfirst($row->jenjang))
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-warning edit" data-id="'.$row->id.'">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete" data-id="'.$row->id.'">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    ';
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

        ProgramStudiModel::create($validated);

        return redirect()->route('admin.program-studi.index')->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $prodi = ProgramStudiModel::findOrFail($id);

        $title = 'Manajemen Program Studi';
        $breadcrumb = [
            'title' => 'Program Studi',
            'list' => ['Program Studi', 'Edit']
        ];

        return view('admin.program-studi.edit', compact('prodi', 'title', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|max:5',
            'nama' => 'required|max:100',
            'jenjang' => 'required|in:D3,D4',
        ]);

        $prodi = ProgramStudiModel::findOrFail($id);
        $prodi->kode = $request->kode;
        $prodi->nama = $request->nama;
        $prodi->jenjang = $request->jenjang;
        $prodi->save();

        return redirect()->route('admin.program-studi.index')->with('success', 'Program Studi berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $programStudi = ProgramStudiModel::findOrFail($id);
        $programStudi->delete();

        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}