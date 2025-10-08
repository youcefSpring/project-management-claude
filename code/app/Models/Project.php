<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'manager_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Define status constants
     */
    const STATUS_PLANNING = 'planning';

    const STATUS_ACTIVE = 'active';

    const STATUS_ON_HOLD = 'on_hold';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PLANNING,
            self::STATUS_ACTIVE,
            self::STATUS_ON_HOLD,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }

    /**
     * Check if project has specific status
     */
    public function hasStatus(string $status): bool
    {
        return $this->status === $status;
    }

    /**
     * Check if project is planning
     */
    public function isPlanning(): bool
    {
        return $this->hasStatus(self::STATUS_PLANNING);
    }

    /**
     * Check if project is active
     */
    public function isActive(): bool
    {
        return $this->hasStatus(self::STATUS_ACTIVE);
    }

    /**
     * Check if project is on hold
     */
    public function isOnHold(): bool
    {
        return $this->hasStatus(self::STATUS_ON_HOLD);
    }

    /**
     * Check if project is completed
     */
    public function isCompleted(): bool
    {
        return $this->hasStatus(self::STATUS_COMPLETED);
    }

    /**
     * Check if project is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->hasStatus(self::STATUS_CANCELLED);
    }

    /**
     * Get the project manager (legacy - for backward compatibility)
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get project members with their roles
     */
    public function members()
    {
        return $this->belongsToMany(User::class)
            ->using(ProjectUser::class)
            ->withPivot(['roles', 'is_active', 'joined_at', 'left_at'])
            ->withTimestamps()
            ->wherePivot('is_active', true);
    }

    /**
     * Get project memberships
     */
    public function memberships()
    {
        return $this->hasMany(ProjectUser::class);
    }

    /**
     * Get active project memberships
     */
    public function activeMemberships()
    {
        return $this->memberships()->active();
    }

    /**
     * Get members with a specific role
     */
    public function membersWithRole(string $role)
    {
        return $this->members()->whereJsonContains('project_user.roles', $role);
    }

    /**
     * Get project managers (users with manager role in this project)
     */
    public function managers()
    {
        return $this->membersWithRole(ProjectUser::ROLE_MANAGER);
    }

    /**
     * Get project developers
     */
    public function developers()
    {
        return $this->membersWithRole(ProjectUser::ROLE_DEVELOPER);
    }

    /**
     * Get project designers
     */
    public function designers()
    {
        return $this->membersWithRole(ProjectUser::ROLE_DESIGNER);
    }

    /**
     * Get project testers
     */
    public function testers()
    {
        return $this->membersWithRole(ProjectUser::ROLE_TESTER);
    }

    /**
     * Get project clients
     */
    public function clients()
    {
        return $this->membersWithRole(ProjectUser::ROLE_CLIENT);
    }

    /**
     * Add a user to the project with specific roles
     */
    public function addMember(User $user, array $roles): ProjectUser
    {
        return ProjectUser::updateOrCreate(
            [
                'project_id' => $this->id,
                'user_id' => $user->id,
            ],
            [
                'roles' => $roles,
                'is_active' => true,
                'joined_at' => now(),
            ]
        );
    }

    /**
     * Remove a user from the project
     */
    public function removeMember(User $user): bool
    {
        $membership = ProjectUser::where('project_id', $this->id)
            ->where('user_id', $user->id)
            ->first();

        if ($membership) {
            $membership->update([
                'is_active' => false,
                'left_at' => now(),
            ]);
            return true;
        }

        return false;
    }

    /**
     * Check if user is a member of this project
     */
    public function hasMember(User $user): bool
    {
        return $this->activeMemberships()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Check if user has specific role in this project
     */
    public function userHasRole(User $user, string $role): bool
    {
        $membership = $this->activeMemberships()
            ->where('user_id', $user->id)
            ->first();

        return $membership && $membership->hasRole($role);
    }

    /**
     * Get all tasks for this project
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get completed tasks for this project
     */
    public function completedTasks(): HasMany
    {
        return $this->tasks()->where('status', Task::STATUS_COMPLETED);
    }

    /**
     * Get pending tasks for this project
     */
    public function pendingTasks(): HasMany
    {
        return $this->tasks()->whereIn('status', [Task::STATUS_PENDING, Task::STATUS_IN_PROGRESS]);
    }

    /**
     * Get overdue tasks for this project
     */
    public function overdueTasks(): HasMany
    {
        return $this->tasks()->where('due_date', '<', now())
            ->whereNotIn('status', [Task::STATUS_COMPLETED]);
    }

    /**
     * Calculate project completion percentage
     */
    public function getCompletionPercentageAttribute(): float
    {
        $totalTasks = $this->tasks()->count();

        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $this->completedTasks()->count();

        return round(($completedTasks / $totalTasks) * 100, 2);
    }

    /**
     * Get project progress percentage (alias for completion_percentage)
     */
    public function getProgressPercentage(): float
    {
        return $this->completion_percentage;
    }

    /**
     * Calculate total hours spent on project
     */
    public function getTotalHoursAttribute(): float
    {
        return $this->tasks()->sum('total_hours');
    }

    /**
     * Get all team members (users with assigned tasks)
     */
    public function teamMembers()
    {
        return User::whereHas('assignedTasks', function ($query) {
            $query->where('project_id', $this->id);
        })->distinct();
    }

    /**
     * Check if project can be deleted
     */
    public function canBeDeleted(): bool
    {
        // Only allow deletion if no time entries exist
        return ! $this->tasks()->whereHas('timeEntries')->exists();
    }

    /**
     * Check if user can view this project
     */
    public function canBeViewedBy(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager() && $this->manager_id === $user->id) {
            return true;
        }

        // Team members can view projects where they have assigned tasks
        if ($user->canWorkOnTasks()) {
            return $this->tasks()->where('assigned_to', $user->id)->exists();
        }

        // Clients can view projects they're assigned to
        if ($user->isClient()) {
            return $this->tasks()->where('assigned_to', $user->id)->exists() ||
                   $this->manager_id === $user->id;
        }

        return false;
    }

    /**
     * Scope to filter by status
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by manager
     */
    public function scopeByManager($query, int $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    /**
     * Scope to filter projects user has access to
     */
    public function scopeAccessibleBy($query, User $user)
    {
        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isManager()) {
            return $query->where('manager_id', $user->id);
        }

        // For members, show projects where they have assigned tasks
        return $query->whereHas('tasks', function ($taskQuery) use ($user) {
            $taskQuery->where('assigned_to', $user->id);
        });
    }
}
