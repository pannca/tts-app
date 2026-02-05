<?php

use App\Http\Controllers\PuzzleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerPuzzleController;
use App\Http\Controllers\AdminPuzzleController;


// Halaman utama redirect ke login
Route::get('/', function () {
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

// LOGOUT ROUTE (Terpisah untuk akses mudah)
Route::get('/logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');

// AUTH ROUTES (Register, Login, Password Reset, dll)
require __DIR__ . '/auth.php';
