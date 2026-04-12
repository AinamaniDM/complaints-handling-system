<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// ── Guest routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/',          [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Logout ────────────────────────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── User routes ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/my-complaints',             [ComplaintController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/complaints/create',         [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints',               [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{complaint}',    [ComplaintController::class, 'show'])->name('complaints.show');
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');
    Route::post('/complaints/{complaint}/comments', [ComplaintController::class, 'storeComment'])->name('complaints.comments.store');
});

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [ComplaintController::class, 'adminDashboard'])->name('dashboard');

    // Complaints
    Route::get('/complaints',                       [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/export/csv',            [ComplaintController::class, 'exportCsv'])->name('complaints.export.csv');
    Route::get('/complaints/export/pdf',            [ComplaintController::class, 'exportPdf'])->name('complaints.export.pdf');
    Route::get('/complaints/{complaint}',           [ComplaintController::class, 'show'])->name('complaints.show');
    Route::get('/complaints/{complaint}/edit',      [ComplaintController::class, 'edit'])->name('complaints.edit');
    Route::put('/complaints/{complaint}',           [ComplaintController::class, 'update'])->name('complaints.update');
    Route::post('/complaints/{complaint}/comments', [ComplaintController::class, 'storeComment'])->name('complaints.comments.store');

    // Categories (super admin only enforced in controller)
    Route::get('/categories',               [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories',              [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}',    [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Admin management (super admin only enforced in controller)
    Route::get('/admins',                   [AdminController::class, 'index'])->name('admins');
    Route::post('/admins',                  [AdminController::class, 'store'])->name('admins.store');
    Route::put('/admins/{user}/role',       [AdminController::class, 'updateRole'])->name('admins.update-role');
    Route::delete('/admins/{user}',         [AdminController::class, 'destroy'])->name('admins.destroy');
});
