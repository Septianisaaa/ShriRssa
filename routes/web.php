<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CensusController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\GuideController;

// Dashboard Utama (Isi Rincian Per Ruangan)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Sensus Harian & Pasien
Route::get('/census', [CensusController::class, 'index'])->name('census.index');
Route::post('/census/patient', [CensusController::class, 'storePatient'])->name('census.patient.store');
Route::post('/census/discharge/{admission}', [CensusController::class, 'dischargePatient'])->name('census.patient.discharge');

// Export Rekap Sensus CSV/Excel
Route::get('/census/export', [CensusController::class, 'exportMonthly'])->name('census.monthly.export');

// Mutasi Pasien Transfer In / Out (Otomatis Pindah Ruang)
Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');
Route::post('/transfers/{transfer}/accept', [TransferController::class, 'accept'])->name('transfers.accept');
Route::post('/transfers/{transfer}/reject', [TransferController::class, 'reject'])->name('transfers.reject');

// Buku Panduan Pengguna Interaktif (Bukpa)
Route::get('/guide', [GuideController::class, 'index'])->name('guide.index');
