<?php

namespace App\Policies;

use App\Models\TimeEntry;
use App\Models\User;

class TimeEntryPolicy
{
    /**
     * Determine whether the user can view any time entries.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view time entries (filtered by accessibleBy scope)
        return true;
    }

    /**
     * Determine whether the user can view the time entry.
     */
    public function view(User $user, TimeEntry $timeEntry): bool
    {
        return $timeEntry->canBeViewedBy($user);
    }

    /**
     * Determine whether the user can create time entries.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create time entries
        return true;
    }

    /**
     * Determine whether the user can update the time entry.
     */
    public function update(User $user, TimeEntry $timeEntry): bool
    {
        return $timeEntry->canBeEditedBy($user);
    }

    /**
     * Determine whether the user can delete the time entry.
     */
    public function delete(User $user, TimeEntry $timeEntry): bool
    {
        return $timeEntry->canBeDeletedBy($user);
    }

    /**
     * Determine whether the user can restore the time entry.
     */
    public function restore(User $user, TimeEntry $timeEntry): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the time entry.
     */
    public function forceDelete(User $user, TimeEntry $timeEntry): bool
    {
        return $user->isAdmin();
    }
}