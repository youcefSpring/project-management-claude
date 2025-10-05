<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'start_time',
        'end_time',
        'comment',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'duration_hours' => 'decimal:2',
        ];
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_time',
        'end_time',
    ];

    /**
     * Get the task this time entry belongs to
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who created this time entry
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project through the task relationship
     */
    public function project()
    {
        return $this->task->project();
    }

    /**
     * Calculate duration in hours (accessor for the computed column)
     */
    public function getDurationAttribute(): float
    {
        if (! $this->start_time || ! $this->end_time) {
            return 0;
        }

        // Ensure we have Carbon instances
        $startTime = $this->start_time instanceof \Carbon\Carbon ? $this->start_time : \Carbon\Carbon::parse($this->start_time);
        $endTime = $this->end_time instanceof \Carbon\Carbon ? $this->end_time : \Carbon\Carbon::parse($this->end_time);

        return round($startTime->diffInMinutes($endTime) / 60, 2);
    }

    /**
     * Calculate duration in hours (alias for consistency)
     */
    public function getDurationHoursAttribute(): float
    {
        return $this->duration;
    }

    /**
     * Get duration formatted as hours:minutes
     */
    public function getDurationFormattedAttribute(): string
    {
        if (! $this->start_time || ! $this->end_time) {
            return '00:00';
        }

        // Ensure we have Carbon instances
        $startTime = $this->start_time instanceof \Carbon\Carbon ? $this->start_time : \Carbon\Carbon::parse($this->start_time);
        $endTime = $this->end_time instanceof \Carbon\Carbon ? $this->end_time : \Carbon\Carbon::parse($this->end_time);

        $totalMinutes = $startTime->diffInMinutes($endTime);
        $hours = intval($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * Check if time entry overlaps with another time entry for the same user
     */
    public function hasOverlapForUser(): bool
    {
        return self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id ?? 0)
            ->where(function ($query) {
                $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $this->end_time])
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('start_time', '<=', $this->start_time)
                            ->where('end_time', '>=', $this->end_time);
                    });
            })
            ->exists();
    }

    /**
     * Check if user can edit this time entry
     */
    public function canBeEditedBy(User $user): bool
    {
        // Admin can edit any time entry
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can edit time entries for their projects
        if ($user->isManager() && $this->task->project->manager_id === $user->id) {
            return true;
        }

        // Users can edit their own time entries
        if ($this->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete this time entry
     */
    public function canBeDeletedBy(User $user): bool
    {
        // Only the owner or admin can delete
        return $user->isAdmin() || $this->user_id === $user->id;
    }

    /**
     * Check if time entry can be modified (not too old)
     */
    public function canBeModified(): bool
    {
        // Allow modification within 7 days (configurable)
        $modificationDeadline = config('app.time_entry_modification_days', 7);

        return $this->created_at->diffInDays(now()) <= $modificationDeadline;
    }

    /**
     * Validate time entry rules
     */
    public function validateTimeEntry(): array
    {
        $errors = [];

        // Check if end time is after start time
        if ($this->end_time <= $this->start_time) {
            $errors[] = 'End time must be after start time';
        }

        // Check if duration is reasonable (not more than 24 hours)
        if ($this->duration > 24) {
            $errors[] = 'Time entry cannot exceed 24 hours';
        }

        // Check if time entry is not in the future
        if ($this->start_time > now()) {
            $errors[] = 'Time entry cannot be in the future';
        }

        // Check for overlaps
        if ($this->hasOverlapForUser()) {
            $errors[] = 'Time entry overlaps with another entry';
        }

        return $errors;
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('start_time', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by task
     */
    public function scopeForTask($query, int $taskId)
    {
        return $query->where('task_id', $taskId);
    }

    /**
     * Scope to filter by project
     */
    public function scopeForProject($query, int $projectId)
    {
        return $query->whereHas('task', function ($taskQuery) use ($projectId) {
            $taskQuery->where('project_id', $projectId);
        });
    }

    /**
     * Scope to filter time entries user has access to
     */
    public function scopeAccessibleBy($query, User $user)
    {
        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isManager()) {
            return $query->whereHas('task.project', function ($projectQuery) use ($user) {
                $projectQuery->where('manager_id', $user->id);
            });
        }

        // For members, show only their own time entries
        return $query->where('user_id', $user->id);
    }

    /**
     * Get time entries grouped by date
     */
    public static function getGroupedByDate(User $user, Carbon $startDate, Carbon $endDate)
    {
        return self::accessibleBy(self::query(), $user)
            ->dateRange($startDate, $endDate)
            ->with(['task.project', 'user'])
            ->get()
            ->groupBy(function ($entry) {
                return $entry->start_time->format('Y-m-d');
            });
    }

    /**
     * Calculate total hours for a user in a date range
     */
    public static function getTotalHoursForUser(User $user, Carbon $startDate, Carbon $endDate): float
    {
        return self::forUser($user->id)
            ->dateRange($startDate, $endDate)
            ->sum('duration_hours') ?? 0;
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Validate before saving
        static::saving(function ($timeEntry) {
            $errors = $timeEntry->validateTimeEntry();
            if (! empty($errors)) {
                throw new \InvalidArgumentException('Validation failed: '.implode(', ', $errors));
            }
        });
    }
}
