<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserContentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes (PUBLIC & PRIVATE MODE)
|--------------------------------------------------------------------------
|
| Konsep Baru: "Education First"
| Halaman materi, video, dan modul bisa diakses PUBLIK tanpa login.
| Login hanya diperlukan untuk fitur personal (simpan progress, profil, dll).
|
*/

// 1. PUBLIC ROUTES (Akses Bebas)
// --------------------------------------------------------------------------

// Halaman Utama (Landing Page)
Route::get('/', [UserContentController::class, 'home'])->name('home');

// Pusat Belajar (List Kategori)
Route::get('/belajar', [UserContentController::class, 'index'])->name('content.index');

// Detail Topik (List Video & Modul dalam Kategori)
// Menggunakan Slug binding untuk SEO friendly URL
Route::get('/belajar/{category:slug}/{topic:slug?}', [UserContentController::class, 'topic'])->name('content.topic');

// Halaman Nonton Video
Route::get('/nonton/{video:slug}', [UserContentController::class, 'video'])->name('content.video');

// Halaman Baca Modul
Route::get('/baca/{module:slug}', [UserContentController::class, 'module'])->name('content.module');


// 2. AUTHENTICATED ROUTES (Khusus Member)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard member:
    // Opsional: Bisa diarahkan ke halaman khusus member, atau tetap ke Home tapi dengan tampilan "Logged In".
    // Untuk saat ini kita arahkan ke Home agar konsisten.
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    // Profile Management (Bawaan Breeze/Laravel)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include Auth Routes (Login, Register, Logout, Reset Password)
require __DIR__.'/auth.php';