<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenPenggunaController;
use App\Http\Controllers\PeriodeMagangController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProfilAdminModel;
use App\Http\Controllers\ProfilDosenController;
use App\Http\Controllers\ProfilMahasiswaController;
use App\Http\Controllers\UserController;
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

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('index');

Route::get('landing', function () {
    return view('landing.index');
});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::middleware('role:ADMIN')->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
        Route::prefix('periode-magang')->name('periode-magang.')->group(function () {
            Route::get('/', [PeriodeMagangController::class, 'index'])->name('index');
            Route::get('/create', [PeriodeMagangController::class, 'create'])->name('create');
            Route::post('/create', [PeriodeMagangController::class, 'store']);
            Route::get('/{id}/edit', [PeriodeMagangController::class, 'edit'])->name('edit');
            Route::put('/{id}/edit', [PeriodeMagangController::class, 'update']);
            Route::delete('/{id}/delete', [PeriodeMagangController::class, 'show'])->name('delete');
        });
        Route::prefix('manajemen-pengguna')->name('manajemen-pengguna.')->group(function () {
            Route::get('/', [ManajemenPenggunaController::class, 'index'])->name('index');
            Route::get('/create', [ManajemenPenggunaController::class, 'create'])->name('create');
            Route::post('/create', [ManajemenPenggunaController::class, 'store']);
            Route::get('/{id}/edit', [ManajemenPenggunaController::class, 'edit'])->name('edit');
            Route::put('/{id}/edit', [ManajemenPenggunaController::class, 'update']);
            Route::delete('/{id}/delete', [ManajemenPenggunaController::class, 'destroy'])->name('delete');
        });
        Route::prefix('profil')->name('profil.')->group(function () {

            Route::get('/', [ProfilAdminModel::class, 'index'])->name('index');

            Route::prefix('informasi-pengguna')->name('informasi-pengguna.')->group(function () {
                Route::get('/', [ProfilAdminModel::class, 'editInformasiPengguna'])->name('index');
                Route::post('/update', [ProfilAdminModel::class, 'updateInformasiPengguna'])->name('update');
            });
        });
    });

    Route::middleware('role:DOSEN')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('profil')->name('profil.')->group(function () {

            Route::get('/', [ProfilDosenController::class, 'index'])->name('index');

            Route::prefix('informasi-pengguna')->name('informasi-pengguna.')->group(function () {
                Route::get('/', [ProfilDosenController::class, 'editInformasiPengguna'])->name('index');
                Route::post('/update', [ProfilDosenController::class, 'updateInformasiPengguna'])->name('update');
            });

            Route::prefix('informasi-detail')->name('informasi-detail.')->group(function () {
                Route::get('/', [ProfilDosenController::class, 'editInformasiDetail'])->name('index');
                Route::post('/update', [ProfilDosenController::class, 'updateInformasiDetail'])->name('update');
            });

            Route::prefix('minat')->name('minat.')->group(function () {
                Route::get('/', [ProfilDosenController::class, 'editMinat'])->name('index');
                Route::post('/store', [ProfilDosenController::class, 'storeMinat'])->name('store');
                Route::delete('/{id}/delete', [ProfilDosenController::class, 'destroyMinat'])->name('delete');
            });

            Route::prefix('preferensi-lokasi')->name('preferensi-lokasi.')->group(function () {
                Route::get('/', [ProfilDosenController::class, 'editPrefrensiLokasi'])->name('index');
                Route::post('/store', [ProfilDosenController::class, 'storePreferensiLokasi'])->name('store');
                Route::delete('/{id}/delete', [ProfilDosenController::class, 'destroyPreferensiLokasi'])->name('delete');
            });
        });
    });

    Route::middleware('role:MAHASISWA')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::prefix('profil')->name('profil.')->group(function () {

            Route::get('/', [ProfilMahasiswaController::class, 'index'])->name('index');

            Route::prefix('informasi-pengguna')->name('informasi-pengguna.')->group(function () {
                Route::get('/', [ProfilMahasiswaController::class, 'editInformasiPengguna'])->name('index');
                Route::post('/update', [ProfilMahasiswaController::class, 'updateInformasiPengguna'])->name('update');
            });

            Route::prefix('informasi-detail')->name('informasi-detail.')->group(function () {
                Route::get('/', [ProfilMahasiswaController::class, 'editInformasiDetail'])->name('index');
                Route::post('/update', [ProfilMahasiswaController::class, 'updateInformasiDetail'])->name('update');
            });

            Route::prefix('keahlian')->name('keahlian.')->group(function () {
                Route::get('/', [ProfilMahasiswaController::class, 'editKeahlian'])->name('index');
                Route::post('/store', [ProfilMahasiswaController::class, 'storeKeahlian'])->name('store');
                Route::delete('/{id}/delete', [ProfilMahasiswaController::class, 'destroyKeahlian'])->name('delete');

            });

            Route::prefix('minat')->name('minat.')->group(function () {
                Route::get('/', [ProfilMahasiswaController::class, 'editMinat'])->name('index');
                Route::post('/store', [ProfilMahasiswaController::class, 'storeMinat'])->name('store');
                Route::delete('/{id}/delete', [ProfilMahasiswaController::class, 'destroyMinat'])->name('delete');
            });

            Route::prefix('preferensi-lokasi')->name('preferensi-lokasi.')->group(function () {
                Route::get('/', [ProfilMahasiswaController::class, 'editPrefrensiLokasi'])->name('index');
                Route::post('/store', [ProfilMahasiswaController::class, 'storePreferensiLokasi'])->name('store');
                Route::delete('/{id}/delete', [ProfilMahasiswaController::class, 'destroyPreferensiLokasi'])->name('delete');
            });
        });
    });

    Route::middleware('role:DOSEN,MAHASISWA')->group(function () {
    });

    Route::put('password/update', [UserController::class, 'updatePassword'])->name('password.update');
});
