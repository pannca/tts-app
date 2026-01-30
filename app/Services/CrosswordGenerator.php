<?php

namespace App\Services;

class CrosswordGenerator
{
    public function generate($words)
    {
        $size = 15;
        $grid = array_fill(0, $size, array_fill(0, $size, null));
        $placed = [];

        // word pertama
        $first = strtoupper($words[0]['word']);
        $row = 7;
        $col = 3;

        for ($i = 0; $i < strlen($first); $i++) {
            $grid[$row][$col + $i] = $first[$i];
        }

        $placed[] = [
            'word' => $first,
            'clue' => $words[0]['clue'],
            'row' => $row,
            'col' => $col,
            'direction' => 'across'
        ];

        // word berikutnya
        foreach (array_slice($words, 1) as $w) {
            $word = strtoupper($w['word']);
            $done = false;

            foreach ($placed as $p) {
                for ($i = 0; $i < strlen($word); $i++) {
                    for ($j = 0; $j < strlen($p['word']); $j++) {
                        if ($word[$i] == $p['word'][$j]) {
                            // tempel silang
                            if ($p['direction'] == 'across') {
                                $r = $p['row'] - $i;
                                $c = $p['col'] + $j;
                                if ($this->canPlaceVertikal($grid, $word, $r, $c)) {
                                    $this->placeVertikal($grid, $word, $r, $c);
                                    $placed[] = [
                                        'word' => $word,
                                        'clue' => $w['clue'],
                                        'row' => $r,
                                        'col' => $c,
                                        'direction' => 'down'
                                    ];
                                    $done = true;
                                    break 3;
                                }
                            }
                        }
                    }
                }
            }
        }
        return ['grid' => $grid, 'words' => $placed];
    }
}
