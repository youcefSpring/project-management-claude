<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user this notification belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): bool
    {
        if ($this->isRead()) {
            return true;
        }

        return $this->update(['read_at' => now()]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(): bool
    {
        return $this->update(['read_at' => null]);
    }

    /**
     * Get time ago format
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get notification icon based on type
     */
    public function getIconAttribute(): string
    {
        switch($this->type) {
            case 'task_note':
                return 'bi-chat-text';
            case 'task_assignment':
                return 'bi-person-check';
            case 'status_change':
                return 'bi-arrow-repeat';
            case 'mention':
                return 'bi-at';
            case 'intervention':
                return 'bi-exclamation-triangle';
            case 'attachment':
                return 'bi-paperclip';
            default:
                return 'bi-bell';
        }
    }

    /**
     * Get notification color based on type
     */
    public function getColorAttribute(): string
    {
        switch($this->type) {
            case 'task_note':
                return 'text-primary';
            case 'task_assignment':
                return 'text-success';
            case 'status_change':
                return 'text-info';
            case 'mention':
                return 'text-warning';
            case 'intervention':
                return 'text-danger';
            case 'attachment':
                return 'text-secondary';
            default:
                return 'text-muted';
        }
    }

    /**
     * Scope to filter unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope to filter read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope to filter by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter recent notifications
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Create a task note notification
     */
    public static function createTaskNoteNotification(User $user, TaskNote $note): self
    {
        $task = $note->task;
        $author = $note->user;

        switch($note->type) {
            case 'comment':
                $title = "New comment on task: {$task->title}";
                break;
            case 'status_change':
                $title = "Status changed on task: {$task->title}";
                break;
            case 'attachment':
                $title = "New attachment on task: {$task->title}";
                break;
            case 'intervention':
                $title = "Intervention on task: {$task->title}";
                break;
            default:
                $title = "New activity on task: {$task->title}";
                break;
        }

        $message = $note->content ?: "View task for details";
        if (strlen($message) > 100) {
            $message = substr($message, 0, 97) . '...';
        }

        $data = [
            'task_id' => $task->id,
            'note_id' => $note->id,
            'task_title' => $task->title,
            'author_name' => $author->name,
            'note_type' => $note->type,
            'project_id' => $task->project_id,
        ];

        return self::create([
            'user_id' => $user->id,
            'type' => 'task_note',
            'title' => $title,
            'message' => "{$author->name}: {$message}",
            'data' => $data,
        ]);
    }

    /**
     * Create a task assignment notification
     */
    public static function createTaskAssignmentNotification(User $user, Task $task, User $assignedBy): self
    {
        $data = [
            'task_id' => $task->id,
            'task_title' => $task->title,
            'assigned_by' => $assignedBy->name,
            'project_id' => $task->project_id,
        ];

        return self::create([
            'user_id' => $user->id,
            'type' => 'task_assignment',
            'title' => "You've been assigned to a task",
            'message' => "You've been assigned to task: {$task->title} by {$assignedBy->name}",
            'data' => $data,
        ]);
    }

    /**
     * Create a mention notification
     */
    public static function createMentionNotification(User $user, TaskNote $note, User $mentionedBy): self
    {
        $task = $note->task;

        $data = [
            'task_id' => $task->id,
            'note_id' => $note->id,
            'task_title' => $task->title,
            'mentioned_by' => $mentionedBy->name,
            'project_id' => $task->project_id,
        ];

        return self::create([
            'user_id' => $user->id,
            'type' => 'mention',
            'title' => "You were mentioned in a comment",
            'message' => "{$mentionedBy->name} mentioned you in task: {$task->title}",
            'data' => $data,
        ]);
    }

    /**
     * Get notifications for user with organization filtering
     */
    public static function getForUser(User $user, int $limit = 50)
    {
        return self::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread count for user
     */
    public static function getUnreadCountForUser(User $user): int
    {
        return self::where('user_id', $user->id)
            ->unread()
            ->count();
    }

    /**
     * Mark all notifications as read for user
     */
    public static function markAllAsReadForUser(User $user): int
    {
        return self::where('user_id', $user->id)
            ->unread()
            ->update(['read_at' => now()]);
    }
}