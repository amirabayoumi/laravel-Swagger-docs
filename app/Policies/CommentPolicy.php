<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine if the given comment can be updated by the user.
     */
    public function update(User $user, Comment $comment)
    {
        // Allow if user is owner or is admin
        return $user->id === $comment->user_id || $user->is_admin;
    }

    /**
     * Determine if the given comment can be deleted by the user.
     */
    public function delete(User $user, Comment $comment)
    {
        // Allow if user is owner or is admin
        return $user->id === $comment->user_id || $user->is_admin;
    }
}
