<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController; 
use Illuminate\Support\Facades\Route;
use App\Livewire\CategoryManager;
use App\Models\Category;

Route::get('/danh-muc', App\Livewire\CategoryManager::class)->name('categories.index');

Route::get('/calculator', function () {
    return view('calculator');
})->middleware(['auth']);
// 1. Public Routes
Route::get('/', function () { return view('welcome'); });

// Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// 2. Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // DASHBOARD THÔNG MINH (Thay thế cho route view đơn giản cũ)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // NHÓM ADMIN
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/role', [AdminController::class, 'changeRole'])->name('users.role');
        Route::get('/all-notes', [AdminController::class, 'allNotes'])->name('notes.all');
    });

    // NHÓM GHI CHÚ
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

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';