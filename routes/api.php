<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PuzzleApiController;

// API Routes for Puzzle Management
Route::get('/puzzles', [PuzzleApiController::class, 'index']);
Route::post('/puzzles', [PuzzleApiController::class, 'store']);
Route::get('/puzzles/{id}', [PuzzleApiController::class, 'show']);
