<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $breadcrumb = (object) [
        'title' => 'Halaman Home',
        'list' => ['Beranda', 'Dashboard']
    ];
    $page = (object) [
        'title' => 'Selamat datang di halaman home'
    ];
    return view('index', compact('breadcrumb', 'page'));
});

Route::get('landing', function () {
    return view('landing.index');
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::middleware('role:ADMIN')->group(function () {
    });
    Route::middleware('role:DOSEN')->group(function () {
        //
    });
    Route::middleware('role:MAHASISWA')->group(function () {
        //
    });
    Route::middleware('role:DOSEN,MAHASISWA')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        //
    });
});