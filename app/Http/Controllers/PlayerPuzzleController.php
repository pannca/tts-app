<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;
use Inertia\Inertia;

class PlayerPuzzleController extends Controller
{
    public function index()
    {
        $puzzles = Puzzle::all();
        return view('user.dashboard', compact('puzzles'));
    }

    public function play($id)
    {
        $puzzle = Puzzle::findOrFail($id);

        return inertia::render('Play', [
            'puzzle' => $puzzle,
        ]);
    }
}
