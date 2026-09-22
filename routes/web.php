<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CensusController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Auth;

// Root Route: Auto Redirect Based on Auth Status & Role
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return Auth::user()->isSuperAdmin()
        ? redirect()->route('shri.dashboard')
        : redirect()->route('admin_ruang.dashboard');
});

// Portal Pemilihan Login
Route::get('/login', [AuthController::class, 'showPortal'])->name('login');

// ----------------------------------------------------
// ROUTE SEPARATE LOGIN & REGISTRASI PETUGAS SHRI
// ----------------------------------------------------
Route::get('/shri/login', [AuthController::class, 'showShriLogin'])->name('shri.login');
Route::post('/shri/login', [AuthController::class, 'loginShri'])->name('shri.login.post');
Route::get('/shri/register', [AuthController::class, 'showShriRegister'])->name('shri.register');
Route::post('/shri/register', [AuthController::class, 'registerShri'])->name('shri.register.post');

// ----------------------------------------------------
// ROUTE SEPARATE LOGIN ADMIN RUANGAN (TANPA REGISTRASI)
// ----------------------------------------------------
Route::get('/admin-ruang/login', [AuthController::class, 'showAdminRuangLogin'])->name('admin_ruang.login');
Route::post('/admin-ruang/login', [AuthController::class, 'loginAdminRuang'])->name('admin_ruang.login.post');

// Logout Action
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 1. PETUGAS SHRI (SUPERADMIN) PROTECTED ROUTES
// ==========================================
Route::middleware(['auth', 'role:superadmin'])->prefix('shri')->name('shri.')->group(function () {
    // Dashboard (Full View & Edit)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sensus Harian & Pasien
    Route::get('/census', [CensusController::class, 'index'])->name('census.index');
    Route::post('/census/patient', [CensusController::class, 'storePatient'])->name('census.patient.store');
    Route::post('/census/discharge/{admission}', [CensusController::class, 'dischargePatient'])->name('census.patient.discharge');
    Route::get('/census/export', [CensusController::class, 'exportMonthly'])->name('census.monthly.export');

    // Mutasi Pindahan Pasien
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
    Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');
    Route::post('/transfers/{transfer}/accept', [TransferController::class, 'accept'])->name('transfers.accept');
    Route::post('/transfers/{transfer}/reject', [TransferController::class, 'reject'])->name('transfers.reject');

    // Buku Panduan Pengguna
    Route::get('/guide', [GuideController::class, 'index'])->name('guide.index');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Kelola Account Admin Ruangan (User Management)
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/admin-ruang', [UserManagementController::class, 'storeAdminRuang'])->name('users.store_admin_ruang');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});

// ==========================================
// 2. ADMIN RUANGAN (ADMIN) PROTECTED ROUTES
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin-ruang')->name('admin_ruang.')->group(function () {
    // Dashboard (View Only)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sensus Harian & Pasien (Edit)
    Route::get('/census', [CensusController::class, 'index'])->name('census.index');
    Route::post('/census/patient', [CensusController::class, 'storePatient'])->name('census.patient.store');
    Route::post('/census/discharge/{admission}', [CensusController::class, 'dischargePatient'])->name('census.patient.discharge');
    Route::get('/census/export', [CensusController::class, 'exportMonthly'])->name('census.monthly.export');

    // Mutasi Pindahan Pasien (Edit)
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
    Route::post('/transfers', [TransferController::class, 'store'])->name('transfers.store');
    Route::post('/transfers/{transfer}/accept', [TransferController::class, 'accept'])->name('transfers.accept');
    Route::post('/transfers/{transfer}/reject', [TransferController::class, 'reject'])->name('transfers.reject');

    // Buku Panduan Pengguna (Bukpa - View Only)
    Route::get('/guide', [GuideController::class, 'index'])->name('guide.index');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
