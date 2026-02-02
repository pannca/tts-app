<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use Illuminate\Http\Request;
use App\Services\CrosswordGenerator;
use Inertia\Inertia;

class AdminPuzzleController extends Controller
{
    public function index()
    {
        $puzzles = Puzzle::all();
        return view('admin.puzzles.index', compact('puzzles'));
    }

    // FORM CREATE (ADMIN)
    public function create()
    {
        return view('admin.puzzles.create');
    }

    // STORE FROM ADMIN FORM
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'words' => 'required|array|min:8',
        ]);

        $generator = new CrosswordGenerator();
        $result = $generator->generate($request->words);

        Puzzle::create([
            'title' => $request->title,
            'grid' => json_encode($result['grid']),
            'words' => json_encode($result['words']),
        ]);

        return redirect()
            ->route('admin.puzzles.create')
            ->with('success', 'Puzzle berhasil dibuat!');
    }

    public function play($id)
    {
        $puzzle = Puzzle::findOrFail($id);

        return inertia::render('Play', [
            'puzzle' => $puzzle,
        ]);
    }


    public function destroy($id)
    {
        $puzzle = Puzzle::findOrFail($id);
        $puzzle->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Puzzle berhasil dihapus!');
    }
}
