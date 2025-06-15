<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|min:8',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required|min:8',
            ]);

            if (!password_verify($request->current_password, auth()->user()->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah']);
            }

            $user = auth()->user();
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Password berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui password.']);
        }
    }

    public function resetPassword($id)
    {
        DB::beginTransaction();
        try {
            $user = UserModel::findOrFail($id);
            if (!$user) {
                return response()->json(['error' => 'User tidak ditemukan.'], 404);
            }

            $user->password = bcrypt($user->username); // Reset password to the username
            $user->save();

            DB::commit();
            return response()->json(['success' => 'Password berhasil direset.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan saat mereset password.'], 500);
        }
    }
}
