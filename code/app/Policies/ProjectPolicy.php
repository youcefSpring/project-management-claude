<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view projects list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Admin can view all projects
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can view projects they manage
        if ($user->isManager() && $project->manager_id === $user->id) {
            return true;
        }

        // Members can view projects where they have assigned tasks
        if ($user->isMember()) {
            return $project->tasks()->where('assigned_to', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin and managers can create projects
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Admin can update any project
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can update projects they manage
        if ($user->isManager() && $project->manager_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Only admin can delete projects
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        // Only admin can restore projects
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        // Only admin can force delete projects
        return $user->isAdmin();
    }
}
