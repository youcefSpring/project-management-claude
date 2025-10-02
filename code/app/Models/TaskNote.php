<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskNote extends Model
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
        'content',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the task this note belongs to
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who created this note
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
     * Get formatted creation time
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Get time ago format
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Extract mentioned users from content (@username pattern)
     */
    public function getMentionedUsersAttribute(): array
    {
        preg_match_all('/@(\w+)/', $this->content, $matches);

        if (empty($matches[1])) {
            return [];
        }

        // Find users by mentioned usernames (assuming username is name field)
        return User::whereIn('name', $matches[1])
                   ->pluck('id')
                   ->toArray();
    }

    /**
     * Get content with highlighted mentions
     */
    public function getContentWithHighlightedMentionsAttribute(): string
    {
        return preg_replace(
            '/@(\w+)/',
            '<span class="mention">@$1</span>',
            $this->content
        );
    }

    /**
     * Check if note contains mentions
     */
    public function hasMentions(): bool
    {
        return !empty($this->mentioned_users);
    }

    /**
     * Check if user can edit this note
     */
    public function canBeEditedBy(User $user): bool
    {
        // Admin can edit any note
        if ($user->isAdmin()) {
            return true;
        }

        // Manager can edit notes in their projects
        if ($user->isManager() && $this->task->project->manager_id === $user->id) {
            return true;
        }

        // Users can edit their own notes within time limit
        if ($this->user_id === $user->id && $this->canBeModified()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete this note
     */
    public function canBeDeletedBy(User $user): bool
    {
        // Admin can delete any note
        if ($user->isAdmin()) {
            return true;
        }

        // Users can delete their own notes within time limit
        if ($this->user_id === $user->id && $this->canBeModified()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can view this note
     */
    public function canBeViewedBy(User $user): bool
    {
        return $this->task->canBeViewedBy($user);
    }

    /**
     * Check if note can be modified (not too old)
     */
    public function canBeModified(): bool
    {
        // Allow modification within 24 hours (configurable)
        $modificationDeadline = config('app.note_modification_hours', 24);
        return $this->created_at->diffInHours(now()) <= $modificationDeadline;
    }

    /**
     * Get content preview (truncated)
     */
    public function getContentPreviewAttribute(): string
    {
        return \Str::limit($this->content, 100);
    }

    /**
     * Check if note is recent (within last hour)
     */
    public function isRecent(): bool
    {
        return $this->created_at->diffInHours(now()) <= 1;
    }

    /**
     * Scope to filter by task
     */
    public function scopeForTask($query, int $taskId)
    {
        return $query->where('task_id', $taskId);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter recent notes
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope to filter notes with mentions
     */
    public function scopeWithMentions($query)
    {
        return $query->where('content', 'LIKE', '%@%');
    }

    /**
     * Scope to filter notes user has access to
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

        // For members, show notes for tasks they have access to
        return $query->whereHas('task', function ($taskQuery) use ($user) {
            $taskQuery->where('assigned_to', $user->id);
        });
    }

    /**
     * Scope to order by newest first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope to order by oldest first
     */
    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Get notes for a specific task with user information
     */
    public static function getForTaskWithUsers(int $taskId)
    {
        return self::forTask($taskId)
                   ->with('user')
                   ->oldest()
                   ->get();
    }

    /**
     * Search notes by content
     */
    public static function search(string $searchTerm)
    {
        return self::where('content', 'LIKE', "%{$searchTerm}%")
                   ->with(['user', 'task.project'])
                   ->latest()
                   ->get();
    }

    /**
     * Get activity feed for user (notes they can see)
     */
    public static function getActivityFeedForUser(User $user, int $limit = 10)
    {
        return self::accessibleBy(self::query(), $user)
                   ->with(['user', 'task.project'])
                   ->recent()
                   ->latest()
                   ->limit($limit)
                   ->get();
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Send notifications when note is created with mentions
        static::created(function ($note) {
            if ($note->hasMentions()) {
                // Dispatch notification job for mentioned users
                // NotifyMentionedUsers::dispatch($note);
            }
        });

        // Validate content length
        static::saving(function ($note) {
            if (strlen($note->content) > 1000) {
                throw new \InvalidArgumentException('Note content cannot exceed 1000 characters');
            }

            if (empty(trim($note->content))) {
                throw new \InvalidArgumentException('Note content cannot be empty');
            }
        });
    }
}