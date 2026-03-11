<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Post extends Model
{
    use HasFactory, HasFlags;

    protected $fillable = [
        'title',
        'content',
    ];
}