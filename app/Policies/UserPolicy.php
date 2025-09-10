<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view the given user.
     * Admins can view anyone; users can view themselves.
     */
    public function view(User $authUser, User $user)
    {
        return $authUser->role === 'admin' || $authUser->id === $user->id;
    }

    /**
     * Determine whether the user can create users.
     * Only admins can create users.
     */
    public function create(User $authUser)
    {
        return $authUser->role === 'admin';
    }

    /**
     * Determine whether the user can update the given user.
     * Only admins can update users.
     */
    public function update(User $authUser, User $user)
    {
        return $authUser->role === 'admin';
    }

    /**
     * Determine whether the user can delete the given user.
     * Only admins can delete users.
     */
    public function delete(User $authUser, User $user)
    {
        return $authUser->role === 'admin';
    }
}
