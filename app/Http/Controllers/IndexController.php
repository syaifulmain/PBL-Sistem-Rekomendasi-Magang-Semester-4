<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanModel;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $data = PerusahaanModel::all();

        return view('landing.index', compact('data'));
    }
}
