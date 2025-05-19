<?php

namespace App\Http\Controllers;

use App\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilAdminModel extends Controller
{
    public function index()
    {
        $data = AdminModel::where('user_id', auth()->user()->id)->first();
        $title = 'Profil Pengguna';
        $breadcrumb = [
            'title' => 'Profil Pengguna',
            'list' => ['Profil Pengguna']
        ];
        return view('profil.admin.index', compact('title', 'breadcrumb', 'data'));
    }

    public function editInformasiPengguna()
    {
        $title = 'Edit Informasi Pengguna';
        $informasiPengguna = AdminModel::where('id', auth()->user()->admin->id)->first();
        return view('profil.admin.form-informasi-pengguna', compact('title', 'informasiPengguna'));
    }

    public function updateInformasiPengguna(Request $request)
    {
        try {
            DB::beginTransaction();

            $admin = AdminModel::where('id', auth()->user()->admin->id)->first();
            $request->validate([
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);


            if ($request->hasFile('foto_profil')) {
                $file = $request->file('foto_profil');
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid('profile_') . '_' . time() . '.' . $extension;
                $file->storeAs('public/users/foto_profil', $fileName);
                $admin->user->path_foto_profil = 'users/foto_profil/' . $fileName;
                $admin->user->save();
            }

            $admin->save();

            DB::commit();

            return redirect()->route('admin.profil.index')->with('success', 'Informasi pengguna berhasil diperbarui');
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
}
