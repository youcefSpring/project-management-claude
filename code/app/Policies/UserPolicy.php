<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin can view user list
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admin can view any user profile
        if ($user->isAdmin()) {
            return true;
        }

        // Users can view their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin can create users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Admin can update any user
        if ($user->isAdmin()) {
            return true;
        }

        // Users can update their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admin can delete users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Only admin can restore users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only admin can force delete users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view reports.
     */
    public function viewReports(User $user): bool
    {
        // Admin, managers, and HR can view reports
        return $user->isAdmin() || $user->isManager() || $user->isHR() || $user->isAccountant();
    }
}
