<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'content'];

    protected $casts = [
        'flags' => 'array',
    ];

    public function flags()
    {
        return $this->hasMany(PostFlag::class);
    }

    public function flag($name)
    {
        if (!$this->hasFlag($name)) {
            PostFlag::create([
                'post_id' => $this->id,
                'name' => $name,
            ]);
        }
        return $this;
    }

    public function unflag($name)
    {
        PostFlag::where('post_id', $this->id)
            ->where('name', $name)
            ->delete();
        return $this;
    }

    public function hasFlag($name)
    {
        return $this->flags->contains('name', $name);
    }

    public function getFlagsListAttribute()
    {
        return $this->flags->pluck('name')->toArray();
    }
}