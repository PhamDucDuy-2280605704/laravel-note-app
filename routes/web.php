<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// 1. Khách (Chưa đăng nhập)
Route::get('/', function () { return view('welcome'); });

// 2. Auth Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// 3. Đã đăng nhập
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // --- KHU VỰC ADMIN (Bảo vệ bởi Middleware 'role:admin') ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminController::class, 'index'])->name('users.index');
        Route::get('/all-notes', [AdminController::class, 'allNotes'])->name('notes.all');
        Route::post('/users/{user}/role', [AdminController::class, 'changeRole'])->name('users.role');
    });

    // --- QUẢN LÝ GHI CHÚ ---
    Route::controller(NoteController::class)->group(function () {
        Route::get('/danh-sach', 'index')->name('notes.index');
        Route::get('/ghi-chu', 'create')->name('notes.create');
        Route::post('/luu-ghi-chu', 'store')->name('notes.store');
        Route::get('/sua-ghi-chu/{id}', 'edit')->name('notes.edit');
        Route::post('/cap-nhat-ghi-chu/{id}', 'update')->name('notes.update');
        Route::get('/xoa-ghi-chu/{id}', 'destroy')->name('notes.destroy');
        Route::get('/thung-rac', 'trash')->name('notes.trash');
        Route::get('/khoi-phuc/{id}', 'restore')->name('notes.restore');
        Route::get('/xoa-vinh-vien/{id}', 'forceDelete')->name('notes.forceDelete');
        Route::get('/ghim-ghi-chu/{id}', 'pin')->name('notes.pin');
    });

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';