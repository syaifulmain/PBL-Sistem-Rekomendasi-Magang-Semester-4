<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updatePassword(Request $request)
    {

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
    }

    public function resetPassword($id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'User tidak ditemukan']);
        }

        $user->password = bcrypt(12345678);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil direset');
    }
}
