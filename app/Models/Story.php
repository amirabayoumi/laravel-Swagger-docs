<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_published'

    ];

    /**
     * Get the comments for this story.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
