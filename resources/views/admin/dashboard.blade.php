<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: #f8f9fa;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: white;
            padding: 20px 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 22px;
            color: #2d3748;
            font-weight: 600;
        }

        .logout-btn {
            background: #e53e3e;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: #c53030;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        .welcome-text {
            font-size: 20px;
            color: #4a5568;
            margin-bottom: 32px;
            font-weight: 500;
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            max-width: 600px;
        }

        .menu-card {
            background: white;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            text-decoration: none;
            display: block;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .menu-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .card-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            background: #3182ce;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-icon svg {
            width: 24px;
            height: 24px;
            color: white;
        }

        .card-text h3 {
            font-size: 16px;
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .card-text p {
            font-size: 13px;
            color: #718096;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .logout-btn {
                align-self: flex-end;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 24px 16px;
            }

            .menu-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1>Admin Dashboard</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="welcome-text">Selamat Datang, Admin {{ Auth::user()->name }} !</h2>

        <!-- Menu Cards -->
        <div class="menu-grid">
            <!-- Data Puzzle -->
            <a href="{{ route('admin.puzzles.index') }}" class="menu-card">
                <div class="card-content">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="card-text">
                        <h3>Data Puzzle</h3>
                        <p>Kelola puzzle</p>
                    </div>
                </div>
            </a>

            <!-- Create Puzzle -->
            <a href="{{ route('admin.puzzles.create') }}" class="menu-card">
                <div class="card-content">
                    <div class="card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="card-text">
                        <h3>Buat Puzzle</h3>
                        <p>Tambah puzzle baru</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</body>
</html>
