<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        $user = Auth::user();
        if ($user) {
            $role = $user->level;

            return match ($role) {
                UserRole::ADMIN     => redirect()->route('admin.dashboard'),
                UserRole::DOSEN     => redirect()->route('dosen.dashboard'),
                UserRole::MAHASISWA => redirect()->route('mahasiswa.dashboard'),
            };
        }
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = $user->level;

            return match ($role) {
                UserRole::ADMIN     => redirect()->route('admin.dashboard'),
                UserRole::DOSEN     => redirect()->route('dosen.dashboard'),
                UserRole::MAHASISWA => redirect()->route('mahasiswa.dashboard'),
            };
        }

        return redirect()->back()
            ->withErrors(['username' => 'Username atau password salah.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
