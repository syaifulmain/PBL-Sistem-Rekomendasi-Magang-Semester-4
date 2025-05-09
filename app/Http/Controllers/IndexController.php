<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (has_role('ADMIN')) {
            return redirect()->route('admin.dashboard');
        }

        if (has_role('DOSEN')) {
            return redirect()->route('dosen.dashboard');
        }

        if (has_role('MAHASISWA')) {
            return redirect()->route('mahasiswa.dashboard');
        }

        return redirect()->route('login');
    }
}
