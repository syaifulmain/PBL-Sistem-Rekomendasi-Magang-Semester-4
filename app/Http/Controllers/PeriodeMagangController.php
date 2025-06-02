<?php

namespace App\Http\Controllers;

use App\Models\PeriodeMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PeriodeMagangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PeriodeMagangModel::query();

            return DataTables::of($data)
                ->editColumn('semester', fn($row) => ucfirst($row->semester))
                ->editColumn('tanggal_mulai', fn($row) => Carbon::parse($row->tanggal_mulai)->translatedFormat('d F Y'))
                ->editColumn('tanggal_selesai', fn($row) => Carbon::parse($row->tanggal_selesai)->translatedFormat('d F Y'))
                // ->editColumn('tanggal_pendaftaran_mulai', fn($row) => Carbon::parse($row->tanggal_pendaftaran_mulai)->translatedFormat('d F Y'))
                // ->editColumn('tanggal_pendaftaran_selesai', fn($row) => Carbon::parse($row->tanggal_pendaftaran_selesai)->translatedFormat('d F Y'))
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.periode-magang.edit', $row->id);
                    $deleteUrl = route('admin.periode-magang.delete', $row->id);

                    return '
                        <a href="'.$editUrl.'" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm btn-delete" data-url="'.$deleteUrl.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);

        }

        $title = 'Periode Magang';
        $breadcrumb = [
            'title' => 'Periode Magang',
            'list' => ['Periode Magang']
        ];
        return view('admin.periode_magang.index', compact('title', 'breadcrumb'));
    }

    public function create()
    {
        $title = 'Periode Magang';
        $breadcrumb = [
            'title' => 'Periode Magang',
            'list' => ['Periode Magang', 'Create']
        ];
        return view('admin.periode_magang.form', compact('title', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|unique:m_periode_magang,nama',
            'tanggal_mulai' => 'required|date_format:d-m-Y',
            'tanggal_selesai' => 'required|date_format:d-m-Y|after_or_equal:tanggal_mulai',
            'tahun_akademik' => 'required',
            // 'tanggal_pendaftaran_mulai' => 'required|date_format:Y-m-d',
            // 'tanggal_pendaftaran_selesai' => 'required|date_format:Y-m-d|after_or_equal:tanggal_pendaftaran_mulai',
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
        ]);

        $validated['tanggal_mulai'] = Carbon::parse($validated['tanggal_mulai'])->format('Y-m-d');
        $validated['tanggal_selesai'] = Carbon::parse($validated['tanggal_selesai'])->format('Y-m-d');

        PeriodeMagangModel::create($validated);

        return redirect()->route('admin.periode-magang.index')->with('success', 'Periode magang berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $data = PeriodeMagangModel::findOrFail($id);
        $title = 'Periode Magang';
        $breadcrumb = [
            'title' => 'Periode Magang',
            'list' => ['Periode Magang', 'Edit']
        ];
        $data->tanggal_mulai = Carbon::parse($data->tanggal_mulai)->format('d-m-Y');
        $data->tanggal_selesai = Carbon::parse($data->tanggal_selesai)->format('d-m-Y');
        return view('admin.periode_magang.form', compact('data', 'title', 'breadcrumb'));
    }

    public function update(Request $request, string $id)
    {
        $periode = PeriodeMagangModel::findOrFail($id);

        $validated = $request->validate([
            'nama' => [
                'required',
                Rule::unique('m_periode_magang', 'nama')->ignore($id),
            ],
            'tanggal_mulai' => 'required|date_format:d-m-Y',
            'tanggal_selesai' => 'required|date_format:d-m-Y|after_or_equal:tanggal_mulai',
            'tahun_akademik' => 'required',
            // 'tanggal_pendaftaran_mulai' => 'required|date_format:Y-m-d',
            // 'tanggal_pendaftaran_selesai' => 'required|date_format:Y-m-d|after_or_equal:tanggal_pendaftaran_mulai',
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
        ]);

        $validated['tanggal_mulai'] = Carbon::parse($validated['tanggal_mulai'])->format('Y-m-d');
        $validated['tanggal_selesai'] = Carbon::parse($validated['tanggal_selesai'])->format('Y-m-d');

        $periode->update($validated);

        return redirect()->route('admin.periode-magang.index')->with('success', 'Periode magang berhasil diperbarui.');
    }

    public function destroy( $id)
    {
        $periode = PeriodeMagangModel::findOrFail($id);
        $periode->delete();

        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
}
