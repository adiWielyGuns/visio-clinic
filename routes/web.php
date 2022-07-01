<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('layouts/home-pasien');
// });


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::controller(StaffController::class)->group(function () {
        Route::group(['prefix' => 'staff'], function () {
            Route::get('/index', 'index')->name('staff');
            Route::get('/create', 'create')->name('create-staff');
            Route::get('/show', 'show')->name('show-staff');
            Route::get('/datatable', 'datatable')->name('datatable-staff');
            Route::get('/edit', 'edit')->name('edit-staff');
            Route::post('/store', 'store')->name('store-staff');
            Route::post('/update', 'update')->name('update-staff');
            Route::post('/delete', 'delete')->name('delete-staff');
            Route::get('/generate-kode', 'generateKode')->name('generate-kode-staff');
        });
    });

    Route::controller(ItemController::class)->group(function () {
        Route::group(['prefix' => 'item'], function () {
            Route::get('/index', 'index')->name('item');
            Route::get('/create', 'create')->name('create-item');
            Route::get('/show', 'show')->name('show-item');
            Route::get('/datatable', 'datatable')->name('datatable-item');
            Route::get('/edit', 'edit')->name('edit-item');
            Route::post('/store', 'store')->name('store-item');
            Route::post('/update', 'update')->name('update-item');
            Route::post('/delete', 'delete')->name('delete-item');
            Route::get('/generate-kode', 'generateKode')->name('generate-kode-item');
            Route::get('/status', 'status')->name('status-item');
        });
    });

    Route::controller(JadwalDokterController::class)->group(function () {
        Route::group(['prefix' => 'jadwal-dokter'], function () {
            Route::get('/index', 'index')->name('jadwal-dokter');
            Route::get('/create', 'create')->name('create-jadwal-dokter');
            Route::get('/show', 'show')->name('show-jadwal-dokter');
            Route::get('/datatable', 'datatable')->name('datatable-jadwal-dokter');
            Route::get('/edit', 'edit')->name('edit-jadwal-dokter');
            Route::post('/store', 'store')->name('store-jadwal-dokter');
            Route::post('/update', 'update')->name('update-jadwal-dokter');
            Route::post('/delete', 'delete')->name('delete-jadwal-dokter');
            Route::get('/generate-kode', 'generateKode')->name('generate-kode-jadwal-dokter');
            Route::get('/status', 'status')->name('status-jadwal-dokter');
        });
    });

    Route::controller(PasienController::class)->group(function () {
        Route::group(['prefix' => 'pasien'], function () {
            Route::get('/index', 'index')->name('pasien');
            Route::get('/create', 'create')->name('create-pasien');
            Route::get('/show', 'show')->name('show-pasien');
            Route::get('/datatable', 'datatable')->name('datatable-pasien');
            Route::get('/datatable-rekam-medis', 'datatableRekamMedis')->name('datatable-rekam-medis-pasien');
            Route::get('/edit', 'edit')->name('edit-pasien');
            Route::post('/store', 'store')->name('store-pasien');
            Route::post('/update', 'update')->name('update-pasien');
            Route::post('/delete', 'delete')->name('delete-pasien');
            Route::get('/generate-kode', 'generateKode')->name('generate-kode-pasien');
        });
    });

    Route::controller(PemeriksaanController::class)->group(function () {
        Route::group(['prefix' => 'pemeriksaan'], function () {
            Route::get('/index', 'index')->name('pemeriksaan');
            Route::get('/create', 'create')->name('create-pemeriksaan');
            Route::get('/show', 'show')->name('show-pemeriksaan');
            Route::get('/datatable', 'datatable')->name('datatable-pemeriksaan');
            Route::get('/datatable-rekam-medis', 'datatableRekamMedis')->name('datatable-rekam-medis-pemeriksaan');
            Route::get('/edit', 'edit')->name('edit-pemeriksaan');
            Route::post('/store', 'store')->name('store-pemeriksaan');
            Route::post('/update', 'update')->name('update-pemeriksaan');
            Route::post('/delete', 'delete')->name('delete-pemeriksaan');
            Route::get('/generate-kode', 'generateKode')->name('generate-kode-pemeriksaan');
        });
    });

    Route::controller(PembayaranController::class)->group(function () {
        Route::group(['prefix' => 'pembayaran'], function () {
            Route::get('/index', 'index')->name('pembayaran');
            Route::get('/create', 'create')->name('create-pembayaran');
            Route::get('/datatable', 'datatable')->name('datatable-pembayaran');
            Route::get('/edit', 'edit')->name('edit-pembayaran');
            Route::get('/status', 'status')->name('status-pembayaran');
            Route::get('/item-generate', 'itemGenerate')->name('item-generate-pembayaran');
            Route::post('/store', 'store')->name('store-pembayaran');
            Route::post('/update', 'update')->name('update-pembayaran');
            Route::post('/delete', 'delete')->name('delete-pembayaran');
            Route::get('/generate-kode', 'generateKode')->name('generate-kode-pembayaran');
            Route::get('/print', 'print')->name('print-pembayaran');
            Route::get('/laporan', 'laporan')->name('laporan-pembayaran');
        });
    });

    Route::controller(SettingController::class)->group(function () {
        Route::group(['prefix' => 'setting'], function () {
            Route::get('/index', 'index')->name('setting');
            Route::get('/datatable', 'datatable')->name('datatable-setting');
            Route::get('/edit', 'edit')->name('edit-setting');
            Route::get('/status', 'status')->name('status-setting');
            Route::post('/store', 'store')->name('store-setting');
            Route::post('/delete', 'delete')->name('delete-setting');
        });
    });
});
require __DIR__ . '/auth.php';
require __DIR__ . '/auth_pasien.php';
