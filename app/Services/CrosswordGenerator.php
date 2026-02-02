<?php

namespace App\Services;

class CrosswordGenerator
{
    // Ukuran grid crossword (15x15)
    private $size = 15;

    /**
     * Method utama untuk membuat crossword puzzle
     * @param array $words - Array kata dan clue dari admin
     * @return array - Grid dan informasi kata yang ditempatkan
     */
    public function generate($words)
    {
        // Inisialisasi grid kosong 15x15
        $grid = array_fill(0, $this->size, array_fill(0, $this->size, null));
        $placed = []; // Untuk menyimpan kata yang berhasil ditempatkan

        // Urutkan kata dari yang terpanjang ke terpendek
        // Semakin panjang kata, semakin sulit ditempatkan, jadi diprioritaskan dulu
        usort($words, fn($a, $b) => strlen($b['word']) <=> strlen($a['word']));

        // --- TAHAP 1: TEMPATKAN KATA PERTAMA (TERPANJANG) ---
        // Tempatkan kata pertama di tengah grid secara horizontal
        $firstWord = strtoupper($words[0]['word']);
        $row = intdiv($this->size, 2); // Baris tengah
        $col = intdiv($this->size - strlen($firstWord), 2); // Kolom tengah disesuaikan panjang kata

        // Isi grid dengan huruf-huruf dari kata pertama
        for ($i = 0; $i < strlen($firstWord); $i++) {
            $grid[$row][$col + $i] = $firstWord[$i];
        }

        // Simpan informasi kata pertama yang sudah ditempatkan
        $placed[] = [
            'word' => $firstWord,
            'clue' => $words[0]['clue'],
            'row' => $row,
            'col' => $col,
            'direction' => 'across', // Horizontal
            'cells' => $this->getWordCells($row, $col, strlen($firstWord), 'across')
        ];

        // --- TAHAP 2: TEMPATKAN KATA-KATA LAINNYA ---
        foreach (array_slice($words, 1) as $w) {
            $word = strtoupper($w['word']);
            $placedWord = false; // Flag untuk menandai apakah kata berhasil ditempatkan

            // Coba cari kata yang sudah ada di grid yang bisa disambungkan
            foreach ($placed as $p) {
                $pWord = $p['word']; // Kata yang sudah ditempatkan

                // Cari semua kemungkinan huruf yang sama antara kata baru dan kata yang sudah ada
                for ($i = 0; $i < strlen($word); $i++) {
                    for ($j = 0; $j < strlen($pWord); $j++) {

                        // Jika ditemukan huruf yang sama
                        if ($word[$i] === $pWord[$j]) {

                            // Jika kata yang sudah ada horizontal, coba tempatkan kata baru secara vertikal
                            if ($p['direction'] === 'across') {
                                $newRow = $p['row'] + $j;  // Baris dari huruf yang cocok di kata yang sudah ada
                                $newCol = $p['col'] - $i;  // Kolom disesuaikan agar huruf yang sama bertemu

                                // Cek apakah bisa ditempatkan di posisi ini
                                if ($this->canPlace($grid, $word, $newRow, $newCol, 'down')) {
                                    // Jika bisa, tempatkan kata
                                    $this->placeWord($grid, $word, $newRow, $newCol, 'down');

                                    // Simpan informasi kata
                                    $placed[] = [
                                        'word' => $word,
                                        'clue' => $w['clue'],
                                        'row' => $newRow,
                                        'col' => $newCol,
                                        'direction' => 'down', // Vertikal
                                        'cells' => $this->getWordCells($newRow, $newCol, strlen($word), 'down')
                                    ];

                                    $placedWord = true; // Tandai berhasil ditempatkan
                                    break 3; // Keluar dari semua loop
                                }
                            }
                            // Jika kata yang sudah ada vertikal, coba tempatkan kata baru secara horizontal
                            else {
                                $newRow = $p['row'] - $i;  // Baris disesuaikan agar huruf yang sama bertemu
                                $newCol = $p['col'] + $j;  // Kolom dari huruf yang cocok di kata yang sudah ada

                                if ($this->canPlace($grid, $word, $newRow, $newCol, 'across')) {
                                    $this->placeWord($grid, $word, $newRow, $newCol, 'across');

                                    $placed[] = [
                                        'word' => $word,
                                        'clue' => $w['clue'],
                                        'row' => $newRow,
                                        'col' => $newCol,
                                        'direction' => 'across', // Horizontal
                                        'cells' => $this->getWordCells($newRow, $newCol, strlen($word), 'across')
                                    ];

                                    $placedWord = true;
                                    break 3;
                                }
                            }
                        }
                    }
                }
            }

            // --- TAHAP 3: JIKA TIDAK BISA DISAMBUNGKAN ---
            // Coba cari lokasi kosong di grid untuk kata ini
            if (!$placedWord) {
                $location = $this->findEmptyPlace($grid, $word);
                if ($location) {
                    $this->placeWord($grid, $word, $location['row'], $location['col'], $location['direction']);

                    $placed[] = [
                        'word' => $word,
                        'clue' => $w['clue'],
                        'row' => $location['row'],
                        'col' => $location['col'],
                        'direction' => $location['direction'],
                        'cells' => $this->getWordCells($location['row'], $location['col'], strlen($word), $location['direction'])
                    ];
                }
            }
        }

        // Return hasil akhir: grid dan informasi kata
        return [
            'grid' => $grid,
            'words' => $placed
        ];
    }

    /**
     * Cek apakah suatu kata bisa ditempatkan di posisi tertentu
     * @param array $grid - Grid saat ini
     * @param string $word - Kata yang akan ditempatkan
     * @param int $row - Baris start
     * @param int $col - Kolom start
     * @param string $direction - Arah: 'across' (horizontal) atau 'down' (vertikal)
     * @return bool - True jika bisa ditempatkan
     */
    private function canPlace($grid, $word, $row, $col, $direction)
    {
        $len = strlen($word);

        // --- CEK 1: BATAS GRID ---
        if ($direction === 'across') {
            // Cek apakah kata muat dalam grid (horizontal)
            if ($col < 0 || $col + $len > $this->size || $row < 0 || $row >= $this->size) return false;

            // Cek sel sebelum kata: harus kosong
            if ($col > 0 && $grid[$row][$col - 1] !== null) return false;
            // Cek sel setelah kata: harus kosong
            if ($col + $len < $this->size && $grid[$row][$col + $len] !== null) return false;
        } else { // down (vertikal)
            // Cek apakah kata muat dalam grid (vertikal)
            if ($row < 0 || $row + $len > $this->size || $col < 0 || $col >= $this->size) return false;

            // Cek sel di atas kata: harus kosong
            if ($row > 0 && $grid[$row - 1][$col] !== null) return false;
            // Cek sel di bawah kata: harus kosong
            if ($row + $len < $this->size && $grid[$row + $len][$col] !== null) return false;
        }

        // --- CEK 2: HURUF DAN SEL SEKITAR ---
        for ($i = 0; $i < $len; $i++) {
            // Tentukan posisi sel yang sedang dicek
            $r = $direction === 'across' ? $row : $row + $i;
            $c = $direction === 'across' ? $col + $i : $col;

            $cell = $grid[$r][$c]; // Nilai di grid saat ini
            $letter = $word[$i];   // Huruf dari kata yang akan ditempatkan

            // Cek bentrok huruf: jika sel sudah terisi, harus sama hurufnya
            if ($cell !== null && $cell !== $letter) return false;

            // Jika sel kosong, cek sel di sampingnya
            if ($cell === null) {
                if ($direction === 'across') {
                    // Untuk horizontal: cek sel di atas dan bawah
                    if ($r > 0 && $grid[$r - 1][$c] !== null) return false;
                    if ($r < $this->size - 1 && $grid[$r + 1][$c] !== null) return false;
                } else {
                    // Untuk vertikal: cek sel di kiri dan kanan
                    if ($c > 0 && $grid[$r][$c - 1] !== null) return false;
                    if ($c < $this->size - 1 && $grid[$r][$c + 1] !== null) return false;
                }
            }
        }

        return true; // Semua pengecekan lolos
    }

    /**
     * Tempatkan kata ke dalam grid
     * @param array &$grid - Grid yang akan diisi (passed by reference)
     * @param string $word - Kata yang akan ditempatkan
     * @param int $row - Baris start
     * @param int $col - Kolom start
     * @param string $direction - Arah penempatan
     */
    private function placeWord(&$grid, $word, $row, $col, $direction)
    {
        for ($i = 0; $i < strlen($word); $i++) {
            if ($direction === 'across') {
                $grid[$row][$col + $i] = $word[$i]; // Horizontal: kolom bertambah
            } else {
                $grid[$row + $i][$col] = $word[$i]; // Vertikal: baris bertambah
            }
        }
    }

    /**
     * Dapatkan semua sel yang ditempati oleh sebuah kata
     * @param int $row - Baris start
     * @param int $col - Kolom start
     * @param int $length - Panjang kata
     * @param string $direction - Arah kata
     * @return array - Array posisi sel (format: "baris-kolom")
     */
    private function getWordCells($row, $col, $length, $direction)
    {
        $cells = [];
        for ($i = 0; $i < $length; $i++) {
            if ($direction === 'across') {
                $cells[] = $row . '-' . ($col + $i); // Horizontal
            } else {
                $cells[] = ($row + $i) . '-' . $col; // Vertikal
            }
        }
        return $cells;
    }

    /**
     * Cari lokasi kosong di grid untuk menempatkan kata
     * Digunakan ketika kata tidak bisa disambungkan dengan kata lain
     * @param array $grid - Grid saat ini
     * @param string $word - Kata yang akan ditempatkan
     * @return array|null - Lokasi jika ditemukan, null jika tidak
     */
    private function findEmptyPlace($grid, $word)
    {
        // Coba semua kemungkinan posisi di grid
        for ($row = 0; $row < $this->size; $row++) {
            for ($col = 0; $col < $this->size; $col++) {
                // Coba kedua arah: horizontal dan vertikal
                foreach (['across', 'down'] as $direction) {
                    if ($this->canPlace($grid, $word, $row, $col, $direction)) {
                        return compact('row', 'col', 'direction');
                    }
                }
            }
        }
        return null; // Tidak ada lokasi yang cocok
    }

    /**
     * Method untuk debugging: tampilkan grid ke console
     * @param array $grid - Grid yang akan ditampilkan
     */
    private function debugGrid($grid)
    {
        foreach ($grid as $row) {
            foreach ($row as $cell) {
                echo $cell ?: '.'; // Tampilkan huruf atau titik jika kosong
                echo ' ';
            }
            echo "\n";
        }
        echo "\n";
    }
}
