<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Puzzle Management</title>
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
        }

        .header h1 {
            font-size: 22px;
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 14px;
            color: #718096;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Navigation */
        .nav-button {
            background: #4a5568;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .nav-button:hover {
            background: #2d3748;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f7fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            padding: 16px 20px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        tbody tr:hover {
            background: #f7fafc;
        }

        /* ID Column */
        .puzzle-id {
            font-size: 14px;
            color: #718096;
            font-weight: 500;
        }

        /* Title Column */
        .puzzle-title {
            font-size: 15px;
            color: #2d3748;
        }

        /* Delete Button */
        .delete-btn {
            background: #e53e3e;
            color: white;
            padding: 6px 16px;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .delete-btn:hover {
            background: #c53030;
        }

        .delete-btn svg {
            width: 14px;
            height: 14px;
        }

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #a0aec0;
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            margin-bottom: 16px;
            color: #cbd5e0;
        }

        .empty-state h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #718096;
        }

        .empty-state p {
            font-size: 14px;
            color: #a0aec0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 20px 16px;
            }

            th, td {
                padding: 12px 16px;
            }

            .table-container {
                overflow-x: auto;
            }

            table {
                min-width: 600px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1>Puzzle Management</h1>
            <p>Kelola semua puzzle</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navigation -->
        <a href="{{ route('admin.dashboard') }}" class="nav-button">
            Kembali
        </a>

        <!-- Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Puzzle</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($puzzles as $puzzle)
                        <tr>
                            <td>
                                <span class="puzzle-id">#{{ $puzzle->id }}</span>
                            </td>
                            <td>
                                <span class="puzzle-title">{{ $puzzle->title }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.puzzles.destroy', $puzzle->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus puzzle ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3>Belum ada puzzle</h3>
                                    <p>Klik tombol "Tambah Puzzle" untuk membuat</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
