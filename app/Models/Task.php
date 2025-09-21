<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'due_date',
        'assigned_to',
        'total_hours',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'total_hours' => 'decimal:2',
        ];
    }

    /**
     * Define status constants
     */
    const STATUS_TODO = 'à_faire';
    const STATUS_IN_PROGRESS = 'en_cours';
    const STATUS_DONE = 'fait';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_TODO,
            self::STATUS_IN_PROGRESS,
            self::STATUS_DONE,
        ];
    }

    /**
     * Get allowed status transitions
     */
    public static function getAllowedTransitions(): array
    {
        return [
            self::STATUS_TODO => [self::STATUS_IN_PROGRESS],
            self::STATUS_IN_PROGRESS => [self::STATUS_TODO, self::STATUS_DONE],
            self::STATUS_DONE => [self::STATUS_IN_PROGRESS],
        ];
    }

    /**
     * Check if task has specific status
     */
    public function hasStatus(string $status): bool
    {
        return $this->status === $status;
    }

    /**
     * Check if task is todo
     */
    public function isTodo(): bool
    {
        return $this->hasStatus(self::STATUS_TODO);
    }

    /**
     * Check if task is in progress
     */
    public function isInProgress(): bool
    {
        return $this->hasStatus(self::STATUS_IN_PROGRESS);
    }

    /**
     * Check if task is done
     */
    public function isDone(): bool
    {
        return $this->hasStatus(self::STATUS_DONE);
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date &&
               $this->due_date->isPast() &&
               !$this->isDone();
    }

    /**
     * Check if status transition is allowed
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $allowedTransitions = self::getAllowedTransitions();

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    /**
     * Get the project this task belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user assigned to this task
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all time entries for this task
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * Get all notes for this task
     */
    public function notes(): HasMany
    {
        return $this->hasMany(TaskNote::class);
    }

    /**
     * Get time entries for a specific user
     */
    public function timeEntriesForUser(User $user): HasMany
    {
        return $this->timeEntries()->where('user_id', $user->id);
    }

    /**
     * Calculate estimated completion percentage based on hours
     */
    public function getCompletionPercentageAttribute(): float
    {
        if ($this->isDone()) {
            return 100;
        }

        if ($this->isInProgress() && $this->total_hours > 0) {
            // Could be enhanced with estimated hours if available
            return 50; // Simple estimate for in-progress tasks
        }

        return 0;
    }

    /**
     * Get days until due date
     */
    public function getDaysUntilDueAttribute(): ?int
    {
        if (!$this->due_date) {
            return null;
        }

        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Check if user can edit this task
     */
    public function canBeEditedBy(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager() && $this->project->manager_id === $user->id) {
            return true;
        }

        if ($user->isMember() && $this->assigned_to === $user->id) {
            return true; // Members can update their own task status
        }

        return false;
    }

    /**
     * Check if user can view this task
     */
    public function canBeViewedBy(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager() && $this->project->manager_id === $user->id) {
            return true;
        }

        if ($user->isMember() && $this->assigned_to === $user->id) {
            return true;
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
     * Scope to filter by assigned user
     */
    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope to filter overdue tasks
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', [self::STATUS_DONE]);
    }

    /**
     * Scope to filter tasks user has access to
     */
    public function scopeAccessibleBy($query, User $user)
    {
        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isManager()) {
            return $query->whereHas('project', function ($projectQuery) use ($user) {
                $projectQuery->where('manager_id', $user->id);
            });
        }

        // For members, show only their assigned tasks
        return $query->where('assigned_to', $user->id);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // When task status changes to done, check if all project tasks are done
        static::updated(function ($task) {
            if ($task->isDirty('status') && $task->isDone()) {
                $project = $task->project;
                $allTasksDone = $project->tasks()->where('status', '!=', self::STATUS_DONE)->count() === 0;

                if ($allTasksDone && $project->isInProgress()) {
                    // Could automatically mark project as completed
                    // $project->update(['status' => Project::STATUS_COMPLETED]);
                }
            }
        });
    }
}