<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PengajuanMagangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PengajuanMagangModel::with(['mahasiswa', 'lowonganMagang.perusahaan'])->select('*');

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <a href="'.route('admin.pengajuan_magang.edit', $row->id).'" 
                           class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger delete" data-id="'.$row->id.'">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    ';
                })
                ->editColumn('status', function($row) {
                    $badge = [
                        'diajukan' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'batal' => 'secondary'
                    ];
                    return '<span class="badge bg-'.$badge[$row->status].'">'.ucfirst($row->status).'</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $title = 'Manajemen Pengajuan Magang';
        $breadcrumb = [
            'title' => 'Pengajuan Magang',
            'list' => ['Pengajuan Magang']
        ];

        return view('admin.pengajuan_magang.index', compact('title', 'breadcrumb'));
    }

    public function edit($id)
    {
        $pengajuan = PengajuanMagangModel::with(['mahasiswa', 'lowonganMagang.perusahaan'])
            ->findOrFail($id);

        $title = 'Edit Pengajuan Magang';
        $breadcrumb = [
            'title' => 'Pengajuan Magang',
            'list' => ['Pengajuan Magang', 'Edit']
        ];

        return view('admin.pengajuan_magang.form', compact('pengajuan', 'title', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:diajukan,disetujui,ditolak,batal',
            'catatan' => 'nullable|string|max:500',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
            'transkip' => 'nullable|file|mimes:pdf|max:2048',
            'ktp' => 'nullable|file|mimes:pdf|max:2048',
            'ktm' => 'nullable|file|mimes:pdf|max:2048',
            'sertifikat' => 'nullable|file|mimes:pdf|max:2048',
            'proposal' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $pengajuan = PengajuanMagangModel::findOrFail($id);

        // Handle file uploads
        foreach (['cv', 'transkip', 'ktp', 'ktm', 'sertifikat', 'proposal'] as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($pengajuan->$field) {
                    Storage::delete('public/'.$pengajuan->$field);
                }
                $validated[$field] = $request->file($field)->store('pengajuan-docs', 'public');
            }
        }

        $pengajuan->update($validated);

        return redirect()->route('admin.pengajuan_magang.index')
            ->with('success', 'Pengajuan magang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pengajuan = PengajuanMagangModel::findOrFail($id);
        
        // Delete associated files
        foreach (['cv', 'transkip', 'ktp', 'ktm', 'sertifikat', 'proposal'] as $field) {
            if ($pengajuan->$field) {
                Storage::delete('public/'.$pengajuan->$field);
            }
        }
        
        $pengajuan->delete();

        return response()->json(['success' => 'Pengajuan magang berhasil dihapus']);
    }
}