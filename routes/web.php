<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PuzzleController;
use Illuminate\Support\Facades\Route;


// Halaman utama redirect ke login
Route::get('/', function () {
    return view('auth.login');
});

// AUTHENTICATED ROUTES (Untuk semua user yang login)
Route::middleware('auth')->group(function () {

    // USER ROUTES (Player Puzzle)
    Route::prefix('dashboard')->name('user.')->group(function () {
        Route::get('/', [PuzzleController::class, 'index'])->name('dashboard');
        Route::get('/play/{id}', [PuzzleController::class, 'play'])->name('play');
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
            Route::get('/', [PuzzleController::class, 'indexAdmin'])->name('index');

            // Form create puzzle
            Route::get('/create', [PuzzleController::class, 'create'])->name('create');

            // Store puzzle baru
            Route::post('/', [PuzzleController::class, 'store'])->name('store');

            // Delete puzzle
            Route::delete('/{id}', [PuzzleController::class, 'destroy'])->name('destroy');
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
