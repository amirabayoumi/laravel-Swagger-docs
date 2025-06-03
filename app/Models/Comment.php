<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{


    protected $fillable = [
        'user_id',
        'story_id',
        'content'
    ];

    /**
     * Get the story that owns this comment.
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * Get the user who wrote this comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
