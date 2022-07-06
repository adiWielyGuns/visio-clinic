<?php

use App\Http\Controllers\HomePasienController;
use App\Http\Controllers\LoginPasienController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginPasienController::class, 'create']);

Route::post('login-store-pasien', [LoginPasienController::class, 'store'])->name('login-pasien');
Route::middleware('pasien')->group(function () {

    Route::controller(HomePasienController::class)->group(function () {
        Route::get('/dashboard-pasien', 'index')->name('dashboard-pasien');
        Route::get('/get-jadwal-dokter', 'getJadwalDokter')->name('get-jadwal-dokter');
        Route::get('/antrian', 'antrian')->name('antrian');
        Route::post('/store', 'store')->name('store-reservasi');
        Route::post('/delete', 'delete')->name('delete-reservasi');
        Route::post('/verifikasi-pembayaran', 'verifikasiPembayaran')->name('verifikasi-pembayaran');
    });

    Route::controller(LoginPasienController::class)->group(function () {
        Route::post('/logout-pasien', 'destroy')->name('logout-pasien');
    });
});
