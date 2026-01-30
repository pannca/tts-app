<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuzzleController;

// API Routes for Puzzle Management
Route::get('/puzzles', [PuzzleController::class, 'index']);
Route::post('/puzzles', [PuzzleController::class, 'store']);
Route::get('/puzzles/{id}', [PuzzleController::class, 'show']);
