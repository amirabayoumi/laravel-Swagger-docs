<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Allow user to update their own info or if admin.
     */
    public function update(User $authUser, User $user)
    {
        return $authUser->id === $user->id || $authUser->is_admin;
    }
}
