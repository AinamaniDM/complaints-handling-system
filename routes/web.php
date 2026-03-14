<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ── Guest routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/',        [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Shared auth ───────────────────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── User routes (auth + role:user) ────────────────────────────────────────────
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/my-complaints',       [ComplaintController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/complaints/create',   [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints',         [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{complaint}',    [ComplaintController::class, 'show'])->name('complaints.show');
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');
});

// ── Admin routes (auth + role:admin) ─────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',  [ComplaintController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}',      [ComplaintController::class, 'show'])->name('complaints.show');
    Route::get('/complaints/{complaint}/edit', [ComplaintController::class, 'edit'])->name('complaints.edit');
    Route::put('/complaints/{complaint}',      [ComplaintController::class, 'update'])->name('complaints.update');

    // Manage admins
    Route::get('/admins',    [AdminController::class, 'index'])->name('admins');
    Route::post('/admins',   [AdminController::class, 'store'])->name('admins.store');
    Route::delete('/admins/{user}', [AdminController::class, 'destroy'])->name('admins.destroy');
});