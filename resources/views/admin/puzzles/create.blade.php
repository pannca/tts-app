<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Puzzle</title>
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

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Header */
        .header {
            background: white;
            padding: 20px 24px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 20px;
            color: #2d3748;
            font-weight: 600;
        }

        .back-btn {
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
        }

        .back-btn:hover {
            background: #2d3748;
        }

        /* Success Message */
        .alert-success {
            background: #48bb78;
            color: white;
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Form Card */
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        /* Form Group */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            color: #2d3748;
            background: #f8fafc;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4299e1;
            background: white;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 30px 0;
        }

        /* Words Section */
        .words-title {
            font-size: 16px;
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .words-grid {
            display: grid;
            gap: 16px;
        }

        .word-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 3px solid #4299e1;
        }

        .word-header {
            font-size: 13px;
            color: #718096;
            font-weight: 500;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .word-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .word-inputs input {
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            color: #2d3748;
            background: white;
        }

        .word-inputs input:focus {
            outline: none;
            border-color: #4299e1;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            background: #4299e1;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 30px;
            transition: background 0.2s;
        }

        .submit-btn:hover {
            background: #3182ce;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
                padding: 20px;
            }

            .back-btn {
                align-self: flex-end;
            }

            .form-card {
                padding: 20px;
            }

            .word-inputs {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 18px;
            }

            .word-card {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Create Puzzle</h1>
            <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('admin.puzzles.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="title">Judul Puzzle</label>
                    <input type="text" id="title" name="title" placeholder="Masukkan judul puzzle" required>
                </div>

                <hr class="divider">

                <div>
                    <h3 class="words-title">Minimal isi 10 item kalo lebih juga boleh</h3>

                    <div class="words-grid">
                        @for ($i = 0; $i < 10; $i++)
                            <div class="word-card">
                                <div class="word-header">Word #{{ $i + 1 }}</div>
                                <div class="word-inputs">
                                    <input type="text" name="words[{{ $i }}][word]" placeholder="Word" required>
                                    <input type="text" name="words[{{ $i }}][clue]" placeholder="Clue" required>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <button type="submit" class="submit-btn">Generate Puzzle</button>
            </form>
        </div>
    </div>
</body>

</html>
