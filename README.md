```markdown
# 🧩 Puzzle Games App

Aplikasi web untuk membuat dan memainkan teka-teki silang (crossword) berbasis Laravel dan Vue.js.

## ✨ Fitur Utama

### 👨‍💼 Admin
- Login/logout admin
- Buat puzzle baru (5 kata + clue)
- Generate grid otomatis
- Lihat & hapus puzzle

### 🎮 Player  
- Login/register user
- Lihat daftar puzzle
- Mainkan puzzle interaktif
- Validasi jawaban real-time (dalam pengembangan)

## 🚀 Tech Stack
- **Backend:** Laravel 12 + PHP 8.3
- **Frontend:** Vue.js 3 + Inertia.js
- **Database:** MySQL
- **Auth:** Laravel Breeze
- **Styling:** Vanilla CSS

## 📦 Instalasi Cepat

1. **Clone & setup:**
```bash
git clone <https://github.com/pannca/tts-app.git>
cd tts-app
composer install
npm install
cp .env.example .env
php artisan key:generate
```

2. **Setup database di `.env`**
```env
DB_CONNECTION=mysql
DB_DATABASE=tts_app
DB_USERNAME=root
DB_PASSWORD=
```

3. **Jalankan:**
```bash
php artisan migrate
npm run dev
php artisan serve
```

4. **Buka:** `http://localhost:8000`

## 🗺️ Struktur Route

### User Routes:
- `/dashboard` → Daftar puzzle
- `/play/{id}` → Mainkan puzzle

### Admin Routes:
- `/admin/dashboard` → Dashboard admin
- `/admin/puzzles` → Kelola puzzle
- `/admin/puzzles/create` → Buat puzzle baru

### Auth:
- `/` → Login page
- `/register` → Register page  
- `/logout` → Logout

## 🧠 Algoritma Crossword

Aplikasi otomatis generate grid 15×15 dengan:
1. Urutkan kata dari terpanjang
2. Tempatkan kata pertama di tengah
3. Sambungkan kata lain dengan huruf yang sama
4. Validasi aturan crossword

## 📁 Struktur File Penting
```
app/
├── Services/CrosswordGenerator.php  # Algoritma grid
├── Http/Controllers/PuzzleController.php
├── Http/Controllers/PuzzleApiController.php
└── Models/Puzzle.php

resources/
├── js/Pages/Play.vue       # Game interface
├── views/admin/            # Admin pages
└── views/auth/             # Login/register
```

## 🔧 Untuk Development

**Development mode:**
```bash
npm run dev
php artisan serve
```

**Production build:**
```bash
npm run build
php artisan config:cache
```

## 📄 Lisensi
Open source untuk tujuan pembelajaran.

---
**Happy Puzzling!** 🧩
```

**Versi singkat ini:**
✅ Lebih mudah dibaca  
✅ Fokus pada info penting  
✅ Instalasi lebih simpel  
✅ Cukup untuk developer baru  
✅ Masih cover semua aspek utama
