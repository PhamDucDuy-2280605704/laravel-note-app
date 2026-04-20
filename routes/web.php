<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

// 1. Trang chủ
Route::get('/', function () { 
    return view('welcome'); 
});

// 2. Đăng nhập Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// 3. Nhóm các Route yêu cầu đăng nhập
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () { 
        return view('dashboard'); 
    })->name('dashboard');

    // --- QUẢN LÝ GHI CHÚ ---
    Route::get('/danh-sach', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/ghi-chu', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/luu-ghi-chu', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/sua-ghi-chu/{id}', [NoteController::class, 'edit'])->name('notes.edit');
    Route::post('/cap-nhat-ghi-chu/{id}', [NoteController::class, 'update'])->name('notes.update');
    Route::get('/xoa-ghi-chu/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');
    
    Route::get('/thung-rac', [NoteController::class, 'trash'])->name('notes.trash');
    Route::get('/khoi-phuc/{id}', [NoteController::class, 'restore'])->name('notes.restore');
    Route::get('/xoa-vinh-vien/{id}', [NoteController::class, 'forceDelete'])->name('notes.forceDelete');
    Route::get('/ghim-ghi-chu/{id}', [NoteController::class, 'pin'])->name('notes.pin');

    // --- QUẢN LÝ PROFILE (Đây là chỗ sửa lỗi profile.edit) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';