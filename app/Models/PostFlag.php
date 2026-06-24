<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PostFlag extends Model
{
    protected $table = 'flags';

    protected $fillable = ['name', 'reason', 'flaggable_id', 'flaggable_type'];

   
    public function flaggable(): MorphTo
    {
        return $this->morphTo();
    }
}