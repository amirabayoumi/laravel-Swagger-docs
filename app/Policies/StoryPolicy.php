<?php

namespace App\Policies;

use App\Models\Story;
use App\Models\User;

class StoryPolicy
{
    /**
     * Determine if the given story can be updated by the user.
     */
    public function update(User $user, Story $story)
    {
        // Allow if user is owner or is admin
        return $user->id === $story->user_id || $user->is_admin;
    }

    /**
     * Determine if the given story can be deleted by the user.
     */
    public function delete(User $user, Story $story)
    {
        // Allow if user is owner or is admin
        return $user->id === $story->user_id || $user->is_admin;
    }
}
