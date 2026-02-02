# 🧩 Puzzle Games App

Aplikasi web untuk membuat dan memainkan teka-teki silang (crossword) berbasis Laravel dan Vue.js (tanpa API).

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
- Validasi jawaban real-time

## 🚀 Tech Stack
- **Backend:** Laravel 12 + PHP 8.3  
- **Frontend:** Vue.js 3 + Inertia.js  
- **Database:** MySQL  
- **Auth:** Laravel Breeze  
- **Styling:** Vanilla CSS  

> ⚠️ Aplikasi ini menggunakan **web routes (Inertia)**, bukan REST API.

## 📦 Instalasi Cepat

1. **Clone & setup:**
```bash
git clone https://github.com/pannca/tts-app.git
cd tts-app
composer install
npm install
cp .env.example .env
php artisan key:generate
````

2. **Setup database di `.env`**

```env
DB_CONNECTION=mysql
DB_DATABASE=tts_app
DB_USERNAME=root
DB_PASSWORD=
```

3. **Jalankan migrasi & server:**

```bash
php artisan migrate
npm run dev
php artisan serve
```

4. **Buka aplikasi:**

```
http://localhost:8000
```

## 🗺️ Struktur Route

### User Routes:

* `/dashboard` → Daftar puzzle
* `/play/{id}` → Mainkan puzzle

### Admin Routes:

* `/admin/dashboard` → Dashboard admin
* `/admin/puzzles` → Kelola puzzle
* `/admin/puzzles/create` → Buat puzzle baru

### Auth:

* `/` → Login page
* `/register` → Register page
* `/logout` → Logout

## 🧠 Algoritma Crossword

Aplikasi otomatis generate grid 15×15 dengan langkah:

1. Urutkan kata dari yang terpanjang
2. Tempatkan kata pertama di tengah
3. Sambungkan kata lain dengan huruf yang sama
4. Validasi aturan crossword (tidak tabrakan & masih dalam grid)

## 📁 Struktur File Penting

```
app/
├── Services/CrosswordGenerator.php   # Algoritma grid
├── Http/Controllers/PlayerPuzzleController.php
├── Http/Controllers/AdminPuzzleController.php
└── Models/Puzzle.php

resources/
├── js/Pages/Play.vue        # Game interface
├── views/admin/             # Admin pages
├── views/user/              # Player pages
└── views/auth/              # Login/register
```

## 🔧 Untuk Development

**Mode development:**

```bash
npm run dev
php artisan serve
```

**Build production:**

```bash
npm run build
php artisan config:cache
```

## 📄 Lisensi

Open source untuk tujuan pembelajaran.

---

**Happy Puzzling!** 🧩
