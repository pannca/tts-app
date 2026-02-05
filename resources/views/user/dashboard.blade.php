@extends('layouts.player')

@section('title', 'Puzzle Games')

@section('content')

    <div class="welcome-message">
        <h2>Halo {{ Auth::user()->name }}!</h2>
    </div>

    <div class="puzzles-grid">
        @if(count($puzzles) > 0)
            @foreach ($puzzles as $item)
                <div class="puzzle-card">
                    <div class="puzzle-header">
                        <div class="puzzle-icon">{{ $item->id }}</div>
                        <div class="puzzle-title">{{ $item->title }}</div>
                    </div>

                    <p class="puzzle-info">
                        Solve this puzzle to test your logic and problem-solving skills.
                    </p>

                    <a href="{{ route('user.play', $item->id) }}" class="play-btn">
                        Play
                    </a>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <h2>No Puzzles Available</h2>
                <p>
                    There are currently no puzzles to display. Check back later for new challenges!
                </p>
            </div>
        @endif
    </div>

@endsection


@push('styles')
<style>
.welcome-message {
    background: white;
    padding: 20px 24px;
    border-radius: 10px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.welcome-message h2 {
    font-size: 18px;
    color: #2c3e50;
    font-weight: 500;
}

.puzzles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.puzzle-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border-top: 3px solid #3498db;
}

.puzzle-header {
    display: flex;
    align-items: flex-start;
    margin-bottom: 12px;
}

.puzzle-icon {
    width: 36px;
    height: 36px;
    background: #3498db;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: white;
    font-weight: 600;
    font-size: 16px;
}

.puzzle-title {
    font-size: 16px;
    color: #2c3e50;
    font-weight: 500;
}

.puzzle-info {
    color: #7f8c8d;
    font-size: 13px;
    margin-bottom: 16px;
}

.play-btn {
    background: #3498db;
    color: white;
    padding: 8px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
}

.play-btn:hover {
    background: #2980b9;
}

.empty-state {
    background: white;
    padding: 40px 20px;
    border-radius: 10px;
    text-align: center;
    grid-column: 1 / -1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
</style>
@endpush
