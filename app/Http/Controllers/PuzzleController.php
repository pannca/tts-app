<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use Illuminate\Http\Request;
use App\Services\CrosswordGenerator;

class PuzzleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'words' => 'required|array|min:5',
        ]);

        $generator = new CrosswordGenerator();
        $result = $generator->generate($request->words);

        $puzzle = Puzzle::create([
            'title' => $request->title,
            'grid' => json_encode($result['grid']),
            'words' => json_encode($result['words']),
        ]);

        return response()->json($puzzle);
    }

    public function show($id)
    {
        return Puzzle::findOrFail($id);
    }

    public function index()
    {
        return Puzzle::latest()->get();
    }
}
