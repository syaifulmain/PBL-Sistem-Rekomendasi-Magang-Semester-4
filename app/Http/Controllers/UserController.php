<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui password: ' . $e->getMessage()]);
        }
    }
}
