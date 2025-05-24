<?php

namespace App\Http\Controllers;

use App\Models\DokumenUserModel;
use Illuminate\Http\Request;

class DokumenUserController extends Controller
{
    public function storeDokumenUser(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx|max:5000',
            'default' => 'required|boolean',
            'jenis_dokumen_id' => 'required|exists:m_jenis_dokumen,id',
            'label' => 'nullable|string|max:255',
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
            'label' => $request->input('label') ? $request->input('label') : null,
            'nama' => $fileName,
            'path' => 'users/dokumen/'
        ]);


        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    public function updateDokumenUser(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx|max:5000',
            'default' => 'required|boolean',
            'jenis_dokumen_id' => 'required|exists:m_jenis_dokumen,id',
            'label' => 'nullable|string|max:255',
        ]);

        $dokumen = DokumenUserModel::findOrFail($id);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '_' . time() . '.' . $extension;
            $file->storeAs('public/users/dokumen', $fileName);
        }

        $dokumen->update([
            'user_id' => auth()->user()->id,
            'jenis_dokumen_id' => $request->input('jenis_dokumen_id'),
            'label' => $request->input('label') ? $request->input('label') : null,
            'nama' => $fileName,
            'path' => 'users/dokumen/'
        ]);

        return response()->json(['success' => 'Data berhasil diupdate.']);
    }

    public function destroyDokumenUser($id)
    {
        $dokumen = DokumenUserModel::findOrFail($id);
        $dokumen->delete();

        return response()->json(['success' => 'Data berhasil dihapus.']);
    }

    public function downloadDokumenUser($id)
    {
        $dokumen = DokumenUserModel::findOrFail($id);

        if (!$dokumen->nama) {
            return response()->json(['error' => 'Dokumen tidak ditemukan.'], 404);
        }

        $filePath = storage_path('app/public/' . $dokumen->path . $dokumen->nama);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan.'], 404);
        }

        return response()->download($filePath, $dokumen->nama);
    }
}
