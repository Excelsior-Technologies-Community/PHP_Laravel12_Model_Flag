<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'content'];

    
    public function flags()
    {
        return $this->morphMany(PostFlag::class, 'flaggable');
    }

    public function flag($name, $reason = 'other') 
    {
        if (!$this->hasFlag($name)) {
            $this->flags()->create([
                'name' => $name,
                'reason' => $reason,
            ]);
        }
        return $this;
    }

    public function unflag($name)
    {
        $this->flags()->where('name', $name)->delete();
        return $this;
    }

    public function hasFlag($name)
    {
        return $this->flags->contains('name', $name);
    }
}