<?php

namespace App\Services;

class CrosswordGenerator
{
    private $size = 15;

    public function generate($words)
    {
        $grid = array_fill(0, $this->size, array_fill(0, $this->size, null));
        $placed = [];

        usort($words, fn($a, $b) => strlen($b['word']) <=> strlen($a['word']));

        // === KATA PERTAMA DI TENGAH ===
        $firstWord = strtoupper($words[0]['word']);
        $row = intdiv($this->size, 2);
        $col = intdiv($this->size - strlen($firstWord), 2);

        for ($i = 0; $i < strlen($firstWord); $i++) {
            $grid[$row][$col + $i] = $firstWord[$i];
        }

        $placed[] = [
            'word' => $firstWord,
            'clue' => $words[0]['clue'],
            'row' => $row,
            'col' => $col,
            'direction' => 'across',
            'cells' => $this->getWordCells($row, $col, strlen($firstWord), 'across')
        ];

        // === KATA SELANJUTNYA ===
        foreach (array_slice($words, 1) as $w) {
            $word = strtoupper($w['word']);
            $placedWord = false;

            foreach ($placed as $p) {
                $pWord = $p['word'];

                for ($i = 0; $i < strlen($word); $i++) {
                    for ($j = 0; $j < strlen($pWord); $j++) {
                        if ($word[$i] === $pWord[$j]) {

                            if ($p['direction'] === 'across') {
                                $newRow = $p['row'] + $j;
                                $newCol = $p['col'] - $i;

                                if ($this->canPlace($grid, $word, $newRow, $newCol, 'down')) {
                                    $this->placeWord($grid, $word, $newRow, $newCol, 'down');

                                    $placed[] = [
                                        'word' => $word,
                                        'clue' => $w['clue'],
                                        'row' => $newRow,
                                        'col' => $newCol,
                                        'direction' => 'down',
                                        'cells' => $this->getWordCells($newRow, $newCol, strlen($word), 'down')
                                    ];

                                    $placedWord = true;
                                    break 3;
                                }
                            } else {
                                $newRow = $p['row'] - $i;
                                $newCol = $p['col'] + $j;

                                if ($this->canPlace($grid, $word, $newRow, $newCol, 'across')) {
                                    $this->placeWord($grid, $word, $newRow, $newCol, 'across');

                                    $placed[] = [
                                        'word' => $word,
                                        'clue' => $w['clue'],
                                        'row' => $newRow,
                                        'col' => $newCol,
                                        'direction' => 'across',
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
        }

        return [
            'grid' => $grid,
            'words' => $placed,
        ];
    }

    private function canPlace($grid, $word, $row, $col, $direction)
    {
        $len = strlen($word);

        if ($direction === 'across') {
            if ($col < 0 || $col + $len > $this->size || $row < 0 || $row >= $this->size) return false;
            if ($col > 0 && $grid[$row][$col - 1] !== null) return false;
            if ($col + $len < $this->size && $grid[$row][$col + $len] !== null) return false;
        } else {
            if ($row < 0 || $row + $len > $this->size || $col < 0 || $col >= $this->size) return false;
            if ($row > 0 && $grid[$row - 1][$col] !== null) return false;
            if ($row + $len < $this->size && $grid[$row + $len][$col] !== null) return false;
        }

        for ($i = 0; $i < $len; $i++) {
            $r = $direction === 'across' ? $row : $row + $i;
            $c = $direction === 'across' ? $col + $i : $col;

            $cell = $grid[$r][$c];
            $letter = $word[$i];

            if ($cell !== null && $cell !== $letter) return false;

            if ($cell === null) {
                if ($direction === 'across') {
                    if ($r > 0 && $grid[$r - 1][$c] !== null) return false;
                    if ($r < $this->size - 1 && $grid[$r + 1][$c] !== null) return false;
                } else {
                    if ($c > 0 && $grid[$r][$c - 1] !== null) return false;
                    if ($c < $this->size - 1 && $grid[$r][$c + 1] !== null) return false;
                }
            }
        }

        return true;
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

    private function getWordCells($row, $col, $length, $direction)
    {
        $cells = [];
        for ($i = 0; $i < $length; $i++) {
            if ($direction === 'across') {
                $cells[] = $row . '-' . ($col + $i);
            } else {
                $cells[] = ($row + $i) . '-' . $col;
            }
        }
        return $cells;
    }
}
