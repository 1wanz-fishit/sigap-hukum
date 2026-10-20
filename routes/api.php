<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PengajuanController;
use App\Http\Controllers\Api\Admin\PengajuanController as AdminPengajuanController;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
        Route::middleware('role:pelapor')->prefix('pelapor')->group(function () {
        Route::get('/pengajuan', [PengajuanController::class, 'index']);
        Route::post('/pengajuan', [PengajuanController::class, 'store']);
        Route::get('/pengajuan/{id}', [PengajuanController::class, 'show']);
        Route::post('/pengajuan/{id}/batal', [PengajuanController::class, 'batal']);
    });

        Route::get('/pengajuan', [AdminPengajuanController::class, 'index']);
        Route::get('/pengajuan/{id}', [AdminPengajuanController::class, 'show']);
        Route::post('/pengajuan/{id}/verifikasi', [AdminPengajuanController::class, 'verifikasi']);
        Route::post('/pengajuan/{id}/analisis', [AdminPengajuanController::class, 'analisis']);
    

    Route::middleware('role:super_admin')->prefix('super-admin')->group(function () {
        Route::get('/ping', fn () => ['pesan' => 'Halo super admin']);
    });
});