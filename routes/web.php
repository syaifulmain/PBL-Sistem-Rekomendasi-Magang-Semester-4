<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerusahaanController;
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
    $breadcrumb = (object)[
        'title' => 'Halaman Home',
        'list' => ['Beranda', 'Dashboard']
    ];
    $page = (object)[
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
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::prefix('perusahaan')->name('perusahaan.')->group(function () {
                Route::get('/', [PerusahaanController::class, 'index'])->name('index');
                Route::get('/list', [PerusahaanController::class, 'list'])->name('list');
                Route::get('/create', [PerusahaanController::class, 'create'])->name('create');
                Route::post('/store', [PerusahaanController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [PerusahaanController::class, 'edit'])->name('edit');
                Route::post('/update/{id}', [PerusahaanController::class, 'update'])->name('update');
                Route::post('/delete/{id}', [PerusahaanController::class, 'destroy'])->name('delete');
                Route::get('/detail/{id}', [PerusahaanController::class, 'detail'])->name('detail');
            });
        });
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
