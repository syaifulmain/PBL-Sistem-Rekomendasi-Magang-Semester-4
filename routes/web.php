<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\dosen\MagangDosenController;
use App\Http\Controllers\KegiatanMagangController;
use App\Http\Controllers\LowonganMagangController;
use App\Http\Controllers\mahasiswa\LowonganMagangMahasiswaController;
use App\Http\Controllers\mahasiswa\MagangMahasiswaController;
use App\Http\Controllers\ManajemenPenggunaController;
use App\Http\Controllers\PengajuanMagangController;
use App\Http\Controllers\PeriodeMagangController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RiwayatPengajuanController;
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

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postLogin']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::post('notifications/mark-read/{notification}', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::get('notifications', function () {
        return view('layouts._notifications');
    })->name('notifications.index');

    Route::middleware('role:ADMIN,MAHASISWA')->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::prefix('mitra-perusahaan')->name('mitra-perusahaan.')->group(function () {
            Route::get('/', [PerusahaanController::class, 'index'])->name('index');
            Route::get('/create', [PerusahaanController::class, 'create'])->name('create');
            Route::post('/create', [PerusahaanController::class, 'store']);
            Route::get('/{id}/edit', [PerusahaanController::class, 'edit'])->name('edit');
            Route::put('/{id}/edit', [PerusahaanController::class, 'update']);
            Route::delete('/{id}/delete', [PerusahaanController::class, 'destroy'])->name('delete');
            Route::get('/provinsi', [PerusahaanController::class, 'getProvinsi'])->name('provinsi');
            Route::get('/kabupaten/{id}', [PerusahaanController::class, 'getKabupaten'])->name('kabupaten');
            Route::get('/kecamatan/{id}', [PerusahaanController::class, 'getKecamatan'])->name('kecamatan');
            Route::get('/desa/{id}', [PerusahaanController::class, 'getDesa'])->name('desa');
        });
        Route::prefix('periode-magang')->name('periode-magang.')->group(function () {
            Route::get('/', [PeriodeMagangController::class, 'index'])->name('index');
            Route::get('/create', [PeriodeMagangController::class, 'create'])->name('create');
            Route::post('/create', [PeriodeMagangController::class, 'store']);
            Route::get('/{id}/edit', [PeriodeMagangController::class, 'edit'])->name('edit');
            Route::put('/{id}/edit', [PeriodeMagangController::class, 'update']);
            Route::delete('/{id}/delete', [PeriodeMagangController::class, 'destroy'])->name('delete');
        });
        Route::prefix('manajemen-pengguna')->name('manajemen-pengguna.')->group(function () {
            Route::get('/', [ManajemenPenggunaController::class, 'index'])->name('index');
            Route::get('/create', [ManajemenPenggunaController::class, 'create'])->name('create');
            Route::post('/create', [ManajemenPenggunaController::class, 'store']);
            Route::get('/{id}/edit', [ManajemenPenggunaController::class, 'edit'])->name('edit');
            Route::put('/{id}/edit', [ManajemenPenggunaController::class, 'update']);
            Route::delete('/{id}/delete', [ManajemenPenggunaController::class, 'destroy'])->name('delete');
            Route::get('/import/index', [ManajemenPenggunaController::class, 'importIndex'])->name('import.index');
            Route::post('/import', [ManajemenPenggunaController::class, 'import'])->name('import');
        });

        Route::prefix('program-studi')->name('program-studi.')->group(function () {
            Route::get('/', [ProgramStudiController::class, 'index'])->name('index');
            Route::get('/create', [ProgramStudiController::class, 'create'])->name('create');
            Route::post('/store', [ProgramStudiController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ProgramStudiController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [ProgramStudiController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [ProgramStudiController::class, 'destroy'])->name('delete');

        });

        Route::prefix('lowongan-magang')->name('lowongan-magang.')->group(function(){
            Route::get('/', [LowonganMagangController::class, 'index'])->name('index');
            Route::get('/create', [LowonganMagangController::class, 'create'])->name('create');
            Route::post('/create', [LowonganMagangController::class, 'store']);
            Route::get('/{id}/detail', [LowonganMagangController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [LowonganMagangController::class, 'edit'])->name('edit');
            Route::put('/{id}/edit', [LowonganMagangController::class, 'update']);
            Route::delete('/{id}/delete', [LowonganMagangController::class, 'destroy'])->name('delete');
            Route::get('/perusahaan', [LowonganMagangController::class, 'getPerusahaan'])->name('perusahaan');
            Route::get('/periode-magang', [LowonganMagangController::class, 'getPeriodeMagang'])->name('periode-magang');
            Route::get('/keahlian', [LowonganMagangController::class, 'getKeahlian'])->name('keahlian');
            Route::get('/dokumen', [LowonganMagangController::class, 'getDokumen'])->name('dokumen');
            Route::get('/teknis', [LowonganMagangController::class, 'getKeahlianTeknis'])->name('teknis');
        });

        Route::prefix('kegiatan-magang')->name('kegiatan-magang.')->group(function(){
            Route::get('/', [KegiatanMagangController::class, 'index'])->name('index');
            Route::get('/process/{id}', [KegiatanMagangController::class, 'process'])->name('process');
            Route::post('/process/{id}', [KegiatanMagangController::class, 'store']);
        });

        Route::prefix('riwayat-pengajuan')->name('riwayat-pengajuan.')->group(function(){
            Route::get('/', [RiwayatPengajuanController::class, 'index'])->name('index');
            Route::get('/{id}/detail', [RiwayatPengajuanController::class, 'show'])->name('show');
        });
    });

    Route::middleware('role:DOSEN')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('bimbingan-magang')->name('bimbingan-magang.')->group(function () {
            Route::get('/', [\App\Http\Controllers\dosen\MagangDosenController::class, 'index'])->name('index');
            Route::get('/{id}/monitoring', [\App\Http\Controllers\dosen\MagangDosenController::class, 'monitoring'])->name('monitoring');
            Route::post('/{id}/monitoring/store', [\App\Http\Controllers\dosen\MagangDosenController::class, 'storeEvaluasiBimbingan'])->name('monitoring.store');
            Route::delete('/{id}/monitoring/delete', [\App\Http\Controllers\dosen\MagangDosenController::class, 'destroyEvaluasiBimbingan'])->name('monitoring.delete');
            Route::get('/{id}/download-pdf', [MagangDosenController::class, 'downloadPdf'])->name('logbook.download.pdf');
        });
    });

    Route::middleware('role:MAHASISWA')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('lowongan-magang')->name('lowongan-magang.')->group(function () {
            Route::get('/', [LowonganMagangMahasiswaController::class, 'index'])->name('index');
            Route::get('/{id}/detail', [LowonganMagangMahasiswaController::class, 'show'])->name('detail');
        });

        Route::prefix('pengajuan-magang')->name('pengajuan-magang.')->group(function(){
            Route::get('/', [PengajuanMagangController::class, 'index'])->name('index');
            Route::get('/create', [PengajuanMagangController::class, 'create'])->name('create');
            Route::post('/create', [PengajuanMagangController::class, 'store']);
            Route::get('/{id}/detail', [PengajuanMagangController::class, 'show'])->name('show');
            Route::delete('/{id}/delete', [PengajuanMagangController::class, 'destroy'])->name('delete');
            Route::get('/data-lowongan', [PengajuanMagangController::class, 'getLowongan'])->name('data-lowongan');
            Route::get('/data-lowongan-dokumen/{id}', [PengajuanMagangController::class, 'getLowonganDokumen'])->name('data-lowongan-dokumen');
        });

        Route::prefix('evaluasi-magang')->name('evaluasi-magang.')->group(function () {
            Route::get('/', [\App\Http\Controllers\mahasiswa\MagangMahasiswaController::class, 'index'])->name('index');
            Route::get('/{id}/monitoring', [\App\Http\Controllers\mahasiswa\MagangMahasiswaController::class, 'monitoring'])->name('monitoring');
            Route::post('/{id}/monitoring/log/store', [\App\Http\Controllers\mahasiswa\MagangMahasiswaController::class, 'storeLogMagang'])->name('monitoring.log.store');
            Route::delete('/{id}/monitoring/log/delete', [\App\Http\Controllers\mahasiswa\MagangMahasiswaController::class, 'destroyLogMagang'])->name('monitoring.log.delete');
            Route::get('/{id}/download-pdf', [MagangMahasiswaController::class, 'downloadPdf'])->name('logbook.download.pdf');
            Route::post('/{id}/evaluasi/store', [\App\Http\Controllers\mahasiswa\MagangMahasiswaController::class, 'storeEvaluasiMagang'])->name('evaluasi.store');
        });
    });

    Route::middleware('role:DOSEN,MAHASISWA')->group(function () {
    });

    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');

        Route::middleware('role:ADMIN,DOSEN,MAHASISWA')->group(function () {
            Route::prefix('informasi-pengguna')->name('informasi-pengguna.')->group(function () {
                Route::get('/', [ProfilController::class, 'informasiPengguna'])->name('index');
                Route::post('/update', [ProfilController::class, 'informasiPenggunaUpdate'])->name('update');
            });
        });

        Route::middleware('role:DOSEN,MAHASISWA')->group(function () {
            Route::prefix('informasi-detail')->name('informasi-detail.')->group(function () {
                Route::get('/', [ProfilController::class, 'informasiDetail'])->name('index');
                Route::post('/update', [ProfilController::class, 'informasiDetailUpdate'])->name('update');
            });

            Route::prefix('minat')->name('minat.')->group(function () {
                Route::get('/', [ProfilController::class, 'minat'])->name('index');
                Route::post('/store', [ProfilController::class, 'storeMinat'])->name('store');
                Route::delete('/{id}/delete', [ProfilController::class, 'destroyMinat'])->name('delete');
            });

            Route::prefix('preferensi-lokasi')->name('preferensi-lokasi.')->group(function () {
                Route::get('/', [ProfilController::class, 'preferensiLokasi'])->name('index');
                Route::post('/store', [ProfilController::class, 'storePreferensiLokasi'])->name('store');
                Route::delete('/{id}/delete', [ProfilController::class, 'destroyPreferensiLokasi'])->name('delete');
            });

            Route::prefix('dokumen')->name('dokumen.')->group(function () {
                Route::get('/', [ProfilController::class, 'tambahDokumen'])->name('index');
                Route::post('/store', [ProfilController::class, 'storeDokumen'])->name('store');
                Route::get('/{id}/edit', [ProfilController::class, 'editDokumen'])->name('edit');
                Route::put('/{id}/update', [ProfilController::class, 'updateDokumen'])->name('update');
            });
        });

        Route::middleware('role:MAHASISWA')->group(function () {
            Route::prefix('keahlian')->name('keahlian.')->group(function () {
                Route::get('/', [ProfilController::class, 'keahlian'])->name('index');
                Route::post('/store', [ProfilController::class, 'storeKeahlian'])->name('store');
                Route::delete('/{id}/delete', [ProfilController::class, 'destroyKeahlian'])->name('delete');
            });
        });
    });

    Route::put('password/update', [UserController::class, 'updatePassword'])->name('password.update');
    Route::post('password/reset/{id}', [UserController::class, 'resetPassword'])->name('password.reset');

    Route::prefix('dokumen')->name('dokumen.')->group(function () {
        Route::post('upload-dokumen-user', [\App\Http\Controllers\DokumenUserController::class, 'storeDokumenUser'])->name('upload-dokumen-user');
        Route::put('update-dokumen-user/{id}', [\App\Http\Controllers\DokumenUserController::class, 'updateDokumenUser'])->name('update-dokumen-user');
        Route::delete('delete-dokumen-user/{id}', [\App\Http\Controllers\DokumenUserController::class, 'destroyDokumenUser'])->name('delete-dokumen-user');
        Route::get('download-dokumen-user/{id}', [\App\Http\Controllers\DokumenUserController::class, 'downloadDokumenUser'])->name('download-dokumen-user');
    });
});
