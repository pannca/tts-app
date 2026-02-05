<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

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

        .header {
            background: white;
            padding: 20px 24px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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
        }

        .logout-btn:hover {
            background: #c53030;
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .logout-btn {
                align-self: flex-end;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar / Header -->
    <div class="header">
        <div class="header-content">
            <h1>@yield('header-title', 'Admin Dashboard')</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
    <!-- Page Content -->
    <div class="main-content">
        @yield('content')
    </div>
</body>
</html>
