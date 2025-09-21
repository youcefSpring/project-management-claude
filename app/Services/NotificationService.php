<?php

namespace App\Services;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskNote;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class NotificationService
{
    /**
     * Notification types
     */
    const TYPE_TASK_ASSIGNED = 'task_assigned';
    const TYPE_TASK_UNASSIGNED = 'task_unassigned';
    const TYPE_TASK_STATUS_CHANGED = 'task_status_changed';
    const TYPE_TASK_DELETED = 'task_deleted';
    const TYPE_NOTE_MENTION = 'note_mention';
    const TYPE_PROJECT_STATUS_CHANGED = 'project_status_changed';
    const TYPE_TIME_ENTRY_APPROVED = 'time_entry_approved';
    const TYPE_TIME_ENTRY_REJECTED = 'time_entry_rejected';

    /**
     * Notify user about task assignment
     */
    public function notifyTaskAssigned(Task $task): void
    {
        if (!$task->assignedUser) {
            return;
        }

        $notification = [
            'type' => self::TYPE_TASK_ASSIGNED,
            'title' => 'New task assigned',
            'message' => "You have been assigned to task: {$task->title}",
            'data' => [
                'task_id' => $task->id,
                'task_title' => $task->title,
                'project_id' => $task->project_id,
                'project_title' => $task->project->title,
                'due_date' => $task->due_date?->format('Y-m-d'),
            ],
            'user_id' => $task->assigned_to,
            'created_at' => now(),
        ];

        $this->sendNotification($task->assignedUser, $notification);

        Log::info('Task assignment notification sent', [
            'task_id' => $task->id,
            'user_id' => $task->assigned_to,
        ]);
    }

    /**
     * Notify user about task unassignment
     */
    public function notifyTaskUnassigned(Task $task, int $previousUserId): void
    {
        $user = User::find($previousUserId);
        if (!$user) {
            return;
        }

        $notification = [
            'type' => self::TYPE_TASK_UNASSIGNED,
            'title' => 'Task unassigned',
            'message' => "You have been unassigned from task: {$task->title}",
            'data' => [
                'task_id' => $task->id,
                'task_title' => $task->title,
                'project_title' => $task->project->title,
            ],
            'user_id' => $previousUserId,
            'created_at' => now(),
        ];

        $this->sendNotification($user, $notification);

        Log::info('Task unassignment notification sent', [
            'task_id' => $task->id,
            'user_id' => $previousUserId,
        ]);
    }

    /**
     * Notify about task status change
     */
    public function notifyTaskStatusChanged(Task $task): void
    {
        $recipients = $this->getTaskStakeholders($task);

        $notification = [
            'type' => self::TYPE_TASK_STATUS_CHANGED,
            'title' => 'Task status updated',
            'message' => "Task '{$task->title}' status changed to: {$task->status}",
            'data' => [
                'task_id' => $task->id,
                'task_title' => $task->title,
                'new_status' => $task->status,
                'project_title' => $task->project->title,
            ],
            'created_at' => now(),
        ];

        foreach ($recipients as $user) {
            $notification['user_id'] = $user->id;
            $this->sendNotification($user, $notification);
        }

        Log::info('Task status change notification sent', [
            'task_id' => $task->id,
            'new_status' => $task->status,
            'recipients_count' => count($recipients),
        ]);
    }

    /**
     * Notify about task deletion
     */
    public function notifyTaskDeleted(Task $task): void
    {
        if (!$task->assignedUser) {
            return;
        }

        $notification = [
            'type' => self::TYPE_TASK_DELETED,
            'title' => 'Task deleted',
            'message' => "Task '{$task->title}' has been deleted",
            'data' => [
                'task_title' => $task->title,
                'project_title' => $task->project->title,
            ],
            'user_id' => $task->assigned_to,
            'created_at' => now(),
        ];

        $this->sendNotification($task->assignedUser, $notification);

        Log::info('Task deletion notification sent', [
            'task_id' => $task->id,
            'user_id' => $task->assigned_to,
        ]);
    }

    /**
     * Notify mentioned users in task notes
     */
    public function notifyNoteMentions(TaskNote $note): void
    {
        $mentionedUserIds = $note->mentioned_users;

        if (empty($mentionedUserIds)) {
            return;
        }

        $mentionedUsers = User::whereIn('id', $mentionedUserIds)->get();

        foreach ($mentionedUsers as $user) {
            // Don't notify the author of the note
            if ($user->id === $note->user_id) {
                continue;
            }

            $notification = [
                'type' => self::TYPE_NOTE_MENTION,
                'title' => 'You were mentioned',
                'message' => "{$note->user->name} mentioned you in a task note",
                'data' => [
                    'note_id' => $note->id,
                    'task_id' => $note->task_id,
                    'task_title' => $note->task->title,
                    'author_name' => $note->user->name,
                    'content_preview' => $note->content_preview,
                ],
                'user_id' => $user->id,
                'created_at' => now(),
            ];

            $this->sendNotification($user, $notification);
        }

        Log::info('Note mention notifications sent', [
            'note_id' => $note->id,
            'mentioned_users_count' => count($mentionedUsers),
        ]);
    }

    /**
     * Notify about project status change
     */
    public function notifyProjectStatusChanged(Project $project): void
    {
        $recipients = $this->getProjectStakeholders($project);

        $notification = [
            'type' => self::TYPE_PROJECT_STATUS_CHANGED,
            'title' => 'Project status updated',
            'message' => "Project '{$project->title}' status changed to: {$project->status}",
            'data' => [
                'project_id' => $project->id,
                'project_title' => $project->title,
                'new_status' => $project->status,
            ],
            'created_at' => now(),
        ];

        foreach ($recipients as $user) {
            $notification['user_id'] = $user->id;
            $this->sendNotification($user, $notification);
        }

        Log::info('Project status change notification sent', [
            'project_id' => $project->id,
            'new_status' => $project->status,
            'recipients_count' => count($recipients),
        ]);
    }

    /**
     * Get notifications for user
     */
    public function getUserNotifications(User $user, int $limit = 20): array
    {
        $sessionKey = "notifications.user.{$user->id}";
        $notifications = Session::get($sessionKey, []);

        // Sort by created_at descending
        usort($notifications, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return array_slice($notifications, 0, $limit);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(User $user): int
    {
        $sessionKey = "notifications.user.{$user->id}";
        $notifications = Session::get($sessionKey, []);

        return count(array_filter($notifications, function($notification) {
            return !($notification['read'] ?? false);
        }));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(User $user, string $notificationId): bool
    {
        $sessionKey = "notifications.user.{$user->id}";
        $notifications = Session::get($sessionKey, []);

        foreach ($notifications as &$notification) {
            if ($notification['id'] === $notificationId) {
                $notification['read'] = true;
                Session::put($sessionKey, $notifications);
                return true;
            }
        }

        return false;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(User $user): int
    {
        $sessionKey = "notifications.user.{$user->id}";
        $notifications = Session::get($sessionKey, []);

        $updatedCount = 0;
        foreach ($notifications as &$notification) {
            if (!($notification['read'] ?? false)) {
                $notification['read'] = true;
                $updatedCount++;
            }
        }

        if ($updatedCount > 0) {
            Session::put($sessionKey, $notifications);
        }

        return $updatedCount;
    }

    /**
     * Clear old notifications
     */
    public function clearOldNotifications(User $user, int $daysOld = 30): int
    {
        $sessionKey = "notifications.user.{$user->id}";
        $notifications = Session::get($sessionKey, []);

        $cutoffDate = now()->subDays($daysOld);
        $filteredNotifications = array_filter($notifications, function($notification) use ($cutoffDate) {
            return strtotime($notification['created_at']) > $cutoffDate->timestamp;
        });

        $removedCount = count($notifications) - count($filteredNotifications);

        if ($removedCount > 0) {
            Session::put($sessionKey, array_values($filteredNotifications));
        }

        return $removedCount;
    }

    /**
     * Send real-time notification (for Ajax polling)
     */
    public function getRealtimeNotifications(User $user, ?string $lastCheckTime = null): array
    {
        $notifications = $this->getUserNotifications($user);

        if ($lastCheckTime) {
            $lastCheck = strtotime($lastCheckTime);
            $notifications = array_filter($notifications, function($notification) use ($lastCheck) {
                return strtotime($notification['created_at']) > $lastCheck;
            });
        }

        return [
            'notifications' => array_values($notifications),
            'unread_count' => $this->getUnreadCount($user),
            'check_time' => now()->toISOString(),
        ];
    }

    /**
     * Send notification to user
     */
    private function sendNotification(User $user, array $notification): void
    {
        // Generate unique ID
        $notification['id'] = uniqid('notif_', true);
        $notification['read'] = false;

        // Store in session (in production, you'd use a database table or Redis)
        $sessionKey = "notifications.user.{$user->id}";
        $existingNotifications = Session::get($sessionKey, []);
        $existingNotifications[] = $notification;

        // Keep only last 100 notifications per user
        if (count($existingNotifications) > 100) {
            $existingNotifications = array_slice($existingNotifications, -100);
        }

        Session::put($sessionKey, $existingNotifications);

        // Here you could also:
        // - Send email notifications
        // - Push to WebSocket channels
        // - Send mobile push notifications
        // - Save to database for persistence
    }

    /**
     * Get task stakeholders (people who should be notified about task changes)
     */
    private function getTaskStakeholders(Task $task): array
    {
        $stakeholders = [];

        // Project manager
        if ($task->project->manager) {
            $stakeholders[] = $task->project->manager;
        }

        // Assigned user
        if ($task->assignedUser) {
            $stakeholders[] = $task->assignedUser;
        }

        // Remove duplicates
        return collect($stakeholders)->unique('id')->values()->toArray();
    }

    /**
     * Get project stakeholders
     */
    private function getProjectStakeholders(Project $project): array
    {
        $stakeholders = [];

        // Project manager
        if ($project->manager) {
            $stakeholders[] = $project->manager;
        }

        // Team members (users with assigned tasks)
        $teamMembers = $project->teamMembers()->get();
        foreach ($teamMembers as $member) {
            $stakeholders[] = $member;
        }

        // Remove duplicates
        return collect($stakeholders)->unique('id')->values()->toArray();
    }

    /**
     * Send digest notifications (for daily/weekly summaries)
     */
    public function sendDigestNotifications(string $frequency = 'daily'): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $digestData = $this->generateDigestData($user, $frequency);

            if (!empty($digestData)) {
                $this->sendDigestNotification($user, $digestData, $frequency);
            }
        }
    }

    /**
     * Generate digest data for user
     */
    private function generateDigestData(User $user, string $frequency): array
    {
        $startDate = $frequency === 'daily' ? now()->subDay() : now()->subWeek();

        return [
            'new_tasks_assigned' => Task::where('assigned_to', $user->id)
                                       ->where('created_at', '>=', $startDate)
                                       ->count(),
            'tasks_completed' => Task::where('assigned_to', $user->id)
                                    ->where('status', Task::STATUS_DONE)
                                    ->where('updated_at', '>=', $startDate)
                                    ->count(),
            'overdue_tasks' => Task::where('assigned_to', $user->id)
                                  ->where('due_date', '<', now())
                                  ->whereNotIn('status', [Task::STATUS_DONE])
                                  ->count(),
            'mentions_count' => TaskNote::whereHas('task', function($query) use ($user) {
                                       $query->where('assigned_to', $user->id);
                                   })
                                   ->where('content', 'LIKE', "%@{$user->name}%")
                                   ->where('created_at', '>=', $startDate)
                                   ->count(),
        ];
    }

    /**
     * Send digest notification
     */
    private function sendDigestNotification(User $user, array $digestData, string $frequency): void
    {
        $notification = [
            'type' => "digest_{$frequency}",
            'title' => ucfirst($frequency) . ' summary',
            'message' => $this->generateDigestMessage($digestData),
            'data' => $digestData,
            'user_id' => $user->id,
            'created_at' => now(),
        ];

        $this->sendNotification($user, $notification);
    }

    /**
     * Generate digest message
     */
    private function generateDigestMessage(array $data): string
    {
        $parts = [];

        if ($data['new_tasks_assigned'] > 0) {
            $parts[] = "{$data['new_tasks_assigned']} new tasks assigned";
        }

        if ($data['tasks_completed'] > 0) {
            $parts[] = "{$data['tasks_completed']} tasks completed";
        }

        if ($data['overdue_tasks'] > 0) {
            $parts[] = "{$data['overdue_tasks']} overdue tasks";
        }

        if ($data['mentions_count'] > 0) {
            $parts[] = "{$data['mentions_count']} mentions";
        }

        return empty($parts) ? 'No recent activity' : implode(', ', $parts);
    }
}