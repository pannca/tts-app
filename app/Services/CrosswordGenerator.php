<?php

namespace App\Services;

class CrosswordGenerator
{
    private $size;

    public function generate($words)
    {
        // Validasi input
        if (empty($words)) {
            return null;
        }

        // Sort kata terpanjang dulu
        usort($words, fn($a, $b) => strlen($b['word']) <=> strlen($a['word']));

        $this->size = $this->calculateGridSize($words);

        // Inisialisasi grid
        $grid = array_fill(0, $this->size, array_fill(0, $this->size, null));
        $placedWords = [];

        // === KATA PERTAMA ===
        $firstWord = strtoupper(trim($words[0]['word']));
        $firstClue = $words[0]['clue'];

        if (strlen($firstWord) > $this->size) {
            // Jika kata pertama terlalu panjang, resize grid
            $this->size = strlen($firstWord) + 4;
            $grid = array_fill(0, $this->size, array_fill(0, $this->size, null));
        }

        // Posisi tengah untuk kata pertama
        $row = intdiv($this->size, 2);
        $col = intdiv($this->size - strlen($firstWord), 2);

        // Tempatkan kata pertama
        $this->placeWord($grid, $firstWord, $row, $col, 'across');

        $placedWords[] = [
            'word' => $firstWord,
            'clue' => $firstClue,
            'row' => $row,
            'col' => $col,
            'direction' => 'across'
        ];

        // === KATA SELANJUTNYA ===
        $failedWords = [];

        foreach (array_slice($words, 1) as $index => $wordData) {
            $word = strtoupper(trim($wordData['word']));
            $clue = $wordData['clue'];

            if (empty($word)) {
                continue;
            }

            $placed = false;
            $attempts = 0;
            $maxAttempts = 100;

            // Acak urutan kata yang sudah ditempatkan untuk variasi
            $shuffledPlaced = $placedWords;
            shuffle($shuffledPlaced);

            while (!$placed && $attempts < $maxAttempts) {
                foreach ($shuffledPlaced as $placedWord) {
                    // Cari huruf yang sama
                    for ($i = 0; $i < strlen($word); $i++) {
                        for ($j = 0; $j < strlen($placedWord['word']); $j++) {
                            if ($word[$i] === $placedWord['word'][$j]) {
                                // Tentukan posisi dan arah baru
                                if ($placedWord['direction'] === 'across') {
                                    $newRow = $placedWord['row'] + $j;
                                    $newCol = $placedWord['col'] - $i;
                                    $newDir = 'down';
                                } else {
                                    $newRow = $placedWord['row'] - $i;
                                    $newCol = $placedWord['col'] + $j;
                                    $newDir = 'across';
                                }

                                if ($this->canPlace($grid, $word, $newRow, $newCol, $newDir, $placedWord, $j)) {
                                    $this->placeWord($grid, $word, $newRow, $newCol, $newDir);

                                    $placedWords[] = [
                                        'word' => $word,
                                        'clue' => $clue,
                                        'row' => $newRow,
                                        'col' => $newCol,
                                        'direction' => $newDir
                                    ];

                                    $placed = true;
                                    break 3;
                                }
                            }
                        }
                    }
                }

                if (!$placed) {
                    // Coba tempatkan di posisi kosong
                    for ($r = 0; $r < $this->size && !$placed; $r++) {
                        for ($c = 0; $c < $this->size && !$placed; $c++) {
                            foreach (['across', 'down'] as $dir) {
                                if ($this->canPlace($grid, $word, $r, $c, $dir, null, null)) {
                                    $this->placeWord($grid, $word, $r, $c, $dir);

                                    $placedWords[] = [
                                        'word' => $word,
                                        'clue' => $clue,
                                        'row' => $r,
                                        'col' => $c,
                                        'direction' => $dir
                                    ];

                                    $placed = true;
                                    break 3;
                                }
                            }
                        }
                    }
                }

                $attempts++;
                shuffle($shuffledPlaced); // Acak lagi
            }

            if (!$placed) {
                $failedWords[] = $word;
            }
        }

        // Crop grid untuk menghapus baris/kolom kosong di tepi
        $cropped = $this->cropGrid($grid);
        $grid = $cropped['grid'];
        $rowOffset = $cropped['rowOffset'];
        $colOffset = $cropped['colOffset'];

        // Update koordinat kata
        foreach ($placedWords as &$word) {
            $word['row'] -= $rowOffset;
            $word['col'] -= $colOffset;
        }

        return [
            'size' => ['rows' => count($grid), 'cols' => count($grid[0])],
            'grid' => $grid,
            'words' => $placedWords,
            'failed' => $failedWords,
            'success_rate' => count($placedWords) / count($words) * 100
        ];
    }

    private function calculateGridSize($words)
    {
        $longest = 0;
        $totalLength = 0;

        foreach ($words as $w) {
            $len = strlen(trim($w['word']));
            $totalLength += $len;
            if ($len > $longest) {
                $longest = $len;
            }
        }

        // Ukuran grid: minimal panjang kata terpanjang + margin
        // atau berdasarkan total panjang semua kata
        $baseSize = max($longest + 4, ceil(sqrt($totalLength * 1.5)));

        // Pastikan ukuran ganjil agar ada titik tengah
        return $baseSize % 2 === 0 ? $baseSize + 1 : $baseSize;
    }

    private function canPlace($grid, $word, $row, $col, $direction, $intersectWord = null, $intersectPos = null)
    {
        $len = strlen($word);

        // Cek batas grid
        if ($direction === 'across') {
            if ($col < 0 || $col + $len > $this->size || $row < 0 || $row >= $this->size) {
                return false;
            }
        } else {
            if ($row < 0 || $row + $len > $this->size || $col < 0 || $col >= $this->size) {
                return false;
            }
        }

        // Cek setiap posisi
        for ($i = 0; $i < $len; $i++) {
            $r = $direction === 'across' ? $row : $row + $i;
            $c = $direction === 'across' ? $col + $i : $col;

            // Jika sel sudah terisi
            if ($grid[$r][$c] !== null) {
                // Dan isinya berbeda dengan huruf yang ingin ditempatkan
                if ($grid[$r][$c] !== $word[$i]) {
                    return false;
                }
                // Jika sama, itu adalah titik persilangan (OK)
            } else {
                // Cek sel tetangga (jangan sampai kata saling menempel)
                $neighbors = $this->getNeighbors($r, $c, $direction, $i, $len);
                foreach ($neighbors as $neighbor) {
                    list($nr, $nc) = $neighbor;
                    if ($nr >= 0 && $nr < $this->size && $nc >= 0 && $nc < $this->size) {
                        // Jika ada huruf lain di tetangga (bukan dari kata yang sama)
                        if ($grid[$nr][$nc] !== null) {
                            // Dan posisi ini bukan persilangan
                            if (!($intersectWord &&
                                $intersectWord['row'] === $nr &&
                                $intersectWord['col'] === $nc)) {
                                return false;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    private function getNeighbors($row, $col, $direction, $position, $wordLength)
    {
        $neighbors = [];

        // Untuk across: periksa atas dan bawah
        if ($direction === 'across') {
            $neighbors[] = [$row - 1, $col]; // atas
            $neighbors[] = [$row + 1, $col]; // bawah
            // Untuk ujung kiri
            if ($position === 0) {
                $neighbors[] = [$row, $col - 1];
            }
            // Untuk ujung kanan
            if ($position === $wordLength - 1) {
                $neighbors[] = [$row, $col + 1];
            }
        }
        // Untuk down: periksa kiri dan kanan
        else {
            $neighbors[] = [$row, $col - 1]; // kiri
            $neighbors[] = [$row, $col + 1]; // kanan
            // Untuk ujung atas
            if ($position === 0) {
                $neighbors[] = [$row - 1, $col];
            }
            // Untuk ujung bawah
            if ($position === $wordLength - 1) {
                $neighbors[] = [$row + 1, $col];
            }
        }

        return $neighbors;
    }

    private function placeWord(&$grid, $word, $row, $col, $direction)
    {
        for ($i = 0; $i < strlen($word); $i++) {
            if ($direction === 'across') {
                $grid[$row][$col + $i] = $word[$i];
            } else {
                $grid[$row + $i][$col] = $word[$i];
            }
        }
    }

    private function cropGrid($grid)
    {
        $rows = count($grid);
        $cols = count($grid[0]);

        // Cari batas atas
        $minRow = $rows;
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                if ($grid[$r][$c] !== null) {
                    $minRow = min($minRow, $r);
                    break;
                }
            }
        }

        // Cari batas bawah
        $maxRow = -1;
        for ($r = $rows - 1; $r >= 0; $r--) {
            for ($c = 0; $c < $cols; $c++) {
                if ($grid[$r][$c] !== null) {
                    $maxRow = max($maxRow, $r);
                    break;
                }
            }
        }

        // Cari batas kiri
        $minCol = $cols;
        for ($c = 0; $c < $cols; $c++) {
            for ($r = 0; $r < $rows; $r++) {
                if ($grid[$r][$c] !== null) {
                    $minCol = min($minCol, $c);
                    break;
                }
            }
        }

        // Cari batas kanan
        $maxCol = -1;
        for ($c = $cols - 1; $c >= 0; $c--) {
            for ($r = 0; $r < $rows; $r++) {
                if ($grid[$r][$c] !== null) {
                    $maxCol = max($maxCol, $c);
                    break;
                }
            }
        }

        // Buang area kosong
        $newRows = $maxRow - $minRow + 1;
        $newCols = $maxCol - $minCol + 1;

        $cropped = array_fill(0, $newRows, array_fill(0, $newCols, null));

        for ($r = 0; $r < $newRows; $r++) {
            for ($c = 0; $c < $newCols; $c++) {
                $cropped[$r][$c] = $grid[$r + $minRow][$c + $minCol];
            }
        }

        return [
            'grid' => $cropped,
            'rowOffset' => $minRow,
            'colOffset' => $minCol
        ];
    }
}
