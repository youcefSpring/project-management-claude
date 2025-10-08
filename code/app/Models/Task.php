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
    const STATUS_PENDING = 'pending';

    const STATUS_IN_PROGRESS = 'in_progress';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }

    /**
     * Get allowed status transitions
     */
    public static function getAllowedTransitions(): array
    {
        return [
            self::STATUS_PENDING => [self::STATUS_IN_PROGRESS],
            self::STATUS_IN_PROGRESS => [self::STATUS_PENDING, self::STATUS_COMPLETED],
            self::STATUS_COMPLETED => [self::STATUS_IN_PROGRESS],
            self::STATUS_CANCELLED => [],
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
     * Check if task is pending
     */
    public function isPending(): bool
    {
        return $this->hasStatus(self::STATUS_PENDING);
    }

    /**
     * Check if task is in progress
     */
    public function isInProgress(): bool
    {
        return $this->hasStatus(self::STATUS_IN_PROGRESS);
    }

    /**
     * Check if task is completed
     */
    public function isCompleted(): bool
    {
        return $this->hasStatus(self::STATUS_COMPLETED);
    }

    /**
     * Check if task is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->hasStatus(self::STATUS_CANCELLED);
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        $dueDate = is_string($this->due_date) ? \Carbon\Carbon::parse($this->due_date) : $this->due_date;

        return $dueDate->isPast() && !$this->isCompleted();
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
        if ($this->isCompleted()) {
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
        if (! $this->due_date) {
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

        // Team members can update their own assigned tasks
        if ($user->canWorkOnTasks() && $this->assigned_to === $user->id) {
            return true;
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

        // Team members can view their assigned tasks
        if ($user->canWorkOnTasks() && $this->assigned_to === $user->id) {
            return true;
        }

        // Clients can view tasks in projects they have access to
        if ($user->isClient()) {
            return $this->project->tasks()->where('assigned_to', $user->id)->exists() ||
                   $this->project->manager_id === $user->id;
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
            ->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
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

        // For team members (developers, designers, testers, etc.), show only their assigned tasks
        if ($user->canWorkOnTasks()) {
            return $query->where('assigned_to', $user->id);
        }

        // For HR, accountants, and clients, show no tasks by default
        return $query->whereRaw('1 = 0');
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // When task status changes to completed, check if all project tasks are completed
        static::updated(function ($task) {
            if ($task->isDirty('status') && $task->isCompleted()) {
                $project = $task->project;
                $allTasksCompleted = $project->tasks()->where('status', '!=', self::STATUS_COMPLETED)->count() === 0;

                if ($allTasksCompleted && $project->isActive()) {
                    // Could automatically mark project as completed
                    // $project->update(['status' => Project::STATUS_COMPLETED]);
                }
            }
        });
    }
}
