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
    const STATUS_IN_PROGRESS = 'en_cours';
    const STATUS_COMPLETED = 'terminé';
    const STATUS_CANCELLED = 'annulé';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_IN_PROGRESS,
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
     * Check if project is in progress
     */
    public function isInProgress(): bool
    {
        return $this->hasStatus(self::STATUS_IN_PROGRESS);
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
     * Get the project manager
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
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
        return $this->tasks()->where('status', Task::STATUS_DONE);
    }

    /**
     * Get pending tasks for this project
     */
    public function pendingTasks(): HasMany
    {
        return $this->tasks()->whereIn('status', [Task::STATUS_TODO, Task::STATUS_IN_PROGRESS]);
    }

    /**
     * Get overdue tasks for this project
     */
    public function overdueTasks(): HasMany
    {
        return $this->tasks()->where('due_date', '<', now())
                              ->whereNotIn('status', [Task::STATUS_DONE]);
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
        return !$this->tasks()->whereHas('timeEntries')->exists();
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