<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Puzzle Games</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: white;
            padding: 24px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-text h1 {
            font-size: 24px;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .header-text p {
            font-size: 14px;
            color: #7f8c8d;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        /* Welcome Message */
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

        /* Puzzles Grid */
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
            line-height: 1.4;
        }

        .puzzle-info {
            color: #7f8c8d;
            font-size: 13px;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .play-btn {
            background: #3498db;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            display: inline-block;
        }

        .play-btn:hover {
            background: #2980b9;
        }

        /* Empty State */
        .empty-state {
            background: white;
            padding: 40px 20px;
            border-radius: 10px;
            text-align: center;
            grid-column: 1 / -1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .empty-state h2 {
            color: #7f8c8d;
            margin-bottom: 12px;
            font-weight: 500;
            font-size: 18px;
        }

        .empty-state p {
            color: #95a5a6;
            max-width: 500px;
            margin: 0 auto 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .logout-btn {
                align-self: flex-end;
            }

            .puzzles-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 16px;
            }

            .header, .welcome-message {
                padding: 20px;
            }

            .header-text h1 {
                font-size: 20px;
            }

            .puzzle-card {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-content">
                <div class="header-text">
                    <h1>Puzzle Games</h1>
                    <p>Choose a puzzle to play</p>
                </div>
                <a href="{{ route('logout') }}" class="logout-btn">Logout</a>
            </div>
        </header>

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
                        <p class="puzzle-info">Solve this puzzle to test your logic and problem-solving skills.</p>
                        <a href="{{ route('user.play', $item->id) }}" class="play-btn">Play</a>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <h2>No Puzzles Available</h2>
                    <p>There are currently no puzzles to display. Check back later for new challenges!</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
