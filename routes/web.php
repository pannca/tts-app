<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerPuzzleController;
use App\Http\Controllers\AdminPuzzleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

// Muat semua rute autentikasi terlebih dahulu agar namanya dikenali
require __DIR__ . '/auth.php';

// Halaman utama akan mengarahkan pengguna yang sudah login ke dashboard,
// dan pengguna tamu (guest) ke halaman login.
Route::get('/', function () {
    if (Auth::check()) {
        // Asumsikan ada kolom 'is_admin' di model User Anda
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    }
    return view('auth.login');
});

// AUTHENTICATED ROUTES (Untuk semua user yang login)
Route::middleware('auth')->group(function () {

    // USER ROUTES (Player Puzzle)
    Route::prefix('dashboard')->name('user.')->group(function () {
        Route::get('/user/dashboard', [PlayerPuzzleController::class, 'index'])->name('dashboard');
        Route::get('/play/{id}', [PlayerPuzzleController::class, 'play'])->name('play');
    });


    // ADMIN ROUTES (Management Puzzle)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // CRUD Puzzles
        Route::prefix('puzzles')->name('puzzles.')->group(function () {
            // List semua puzzle
            Route::get('/', [AdminPuzzleController::class, 'index'])->name('index');
            // Form create puzzle
            Route::get('/create', [AdminPuzzleController::class, 'create'])->name('create');
            // Store puzzle baru
            Route::post('/', [AdminPuzzleController::class, 'store'])->name('store');
            // Delete puzzle
            Route::delete('/{id}', [AdminPuzzleController::class, 'destroy'])->name('destroy');
        });
    });
});
