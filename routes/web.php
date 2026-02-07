<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserContentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes (PRIVATE MODE)
|--------------------------------------------------------------------------
|
| Semua halaman di sini diproteksi.
| User WAJIB Login untuk mengakses menu apapun.
|
*/

// 1. ROOT URL: Redirect paksa ke halaman Login jika belum auth
Route::get('/', function () {
    // Jika user iseng buka root tapi sudah login, lempar ke beranda
    if (Auth::check()) {
        return redirect()->route('home');
    }
    // Jika belum, lempar ke login
    return redirect()->route('login');
});

// 2. HALAMAN KHUSUS MEMBER (Harus Login)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Redirect /dashboard (bawaan Breeze) ke Beranda kita
    Route::get('/dashboard', [UserContentController::class, 'home'])->name('dashboard');

    // Halaman Utama (Landing Page versi User Logged In)
    Route::get('/beranda', [UserContentController::class, 'home'])->name('home');

    // Pusat Belajar (Kategori)
    Route::get('/belajar', [UserContentController::class, 'index'])->name('content.index');

    // Detail Topik (List Video & Modul)
    Route::get('/belajar/{category:slug}/{topic:slug?}', [UserContentController::class, 'topic'])->name('content.topic');

    // Nonton Video
    Route::get('/nonton/{video:slug}', [UserContentController::class, 'video'])->name('content.video');

    // Baca Modul
    Route::get('/baca/{module:slug}', [UserContentController::class, 'module'])->name('content.module');

    // Profile Settings (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include Auth Routes (Login, Register, Logout)
require __DIR__.'/auth.php';