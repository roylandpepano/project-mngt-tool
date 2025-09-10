<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function view(User $user, Project $project)
    {
        // Allow owners or admins to view
        return $user->role === 'admin' || $project->user_id === $user->id;
    }

    public function update(User $user, Project $project)
    {
        // Allow owners or admins to update
        return $user->role === 'admin' || $project->user_id === $user->id;
    }

    public function delete(User $user, Project $project)
    {
        // Allow owners or admins to delete
        return $user->role === 'admin' || $project->user_id === $user->id;
    }
}
