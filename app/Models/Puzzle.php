<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'grid',
        'words',
    ];

    protected $casts = [
        'grid' => 'array',
        'words' => 'array',
    ];
}
