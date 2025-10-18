<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskNote;
use App\Models\User;
use App\Models\Notification;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class TaskNoteService
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Get all notes for a specific task
     */
    public function getTaskNotes($taskId)
    {
        $task = Task::findOrFail($taskId);

        // Check if user has access to this task
        $this->authorizeTaskAccess($task);

        return TaskNote::where('task_id', $taskId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new task note with optional attachments
     */
    public function createNote($taskId, array $data, array $files = [])
    {
        $task = Task::findOrFail($taskId);

        // Check if user has access to this task
        $this->authorizeTaskAccess($task);

        // Process attachments if any
        $attachments = [];
        if (!empty($files)) {
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $attachments[] = $this->imageService->uploadAndResize($file);
                }
            }
        }

        $noteData = [
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'content' => $data['content'] ?? '',
            'is_internal' => $data['is_internal'] ?? false,
            'type' => $data['type'] ?? (!empty($attachments) ? 'attachment' : 'comment'),
            'attachments' => !empty($attachments) ? $attachments : null,
            'metadata' => $data['metadata'] ?? null,
        ];

        $note = TaskNote::create($noteData);

        // Send notifications to stakeholders
        $this->notifyTaskStakeholders($note);

        return $note;
    }

    /**
     * Update an existing task note
     */
    public function updateNote($noteId, array $data)
    {
        $note = TaskNote::findOrFail($noteId);

        // Only the note author or admin can update the note
        $this->authorizeNoteModification($note);

        $updateData = [];

        if (isset($data['content'])) {
            $updateData['content'] = $data['content'];
        }

        if (isset($data['is_internal'])) {
            $updateData['is_internal'] = $data['is_internal'];
        }

        $note->update($updateData);

        return $note->fresh();
    }

    /**
     * Delete a task note
     */
    public function deleteNote($noteId)
    {
        $note = TaskNote::findOrFail($noteId);

        // Only the note author or admin can delete the note
        $this->authorizeNoteModification($note);

        $note->delete();

        return true;
    }

    /**
     * Get a specific note by ID
     */
    public function getNote($noteId)
    {
        $note = TaskNote::with(['user', 'task'])->findOrFail($noteId);

        // Check if user has access to this task
        $this->authorizeTaskAccess($note->task);

        return $note;
    }

    /**
     * Get notes for multiple tasks (useful for project overview)
     */
    public function getNotesForTasks(array $taskIds)
    {
        $tasks = Task::whereIn('id', $taskIds)->get();

        // Check access for each task
        foreach ($tasks as $task) {
            $this->authorizeTaskAccess($task);
        }

        return TaskNote::whereIn('task_id', $taskIds)
            ->with(['user', 'task'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('task_id');
    }

    /**
     * Search notes by content
     */
    public function searchNotes($query, $taskId = null)
    {
        $notesQuery = TaskNote::where('content', 'like', "%{$query}%")
            ->with(['user', 'task']);

        if ($taskId) {
            $task = Task::findOrFail($taskId);
            $this->authorizeTaskAccess($task);
            $notesQuery->where('task_id', $taskId);
        } else {
            // If no specific task, only show notes for tasks user has access to
            $user = Auth::user();
            if ($user->role !== 'admin') {
                $notesQuery->whereHas('task', function ($query) use ($user) {
                    $query->where('assigned_to', $user->id)
                        ->orWhereHas('project', function ($projectQuery) use ($user) {
                            $projectQuery->where('created_by', $user->id)
                                ->orWhere('manager_id', $user->id);
                        });
                });
            }
        }

        return $notesQuery->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get recent notes for dashboard
     */
    public function getRecentNotes($limit = 5)
    {
        $user = Auth::user();

        $notesQuery = TaskNote::with(['user', 'task']);

        if ($user->role !== 'admin') {
            $notesQuery->whereHas('task', function ($query) use ($user) {
                $query->where('assigned_to', $user->id)
                    ->orWhereHas('project', function ($projectQuery) use ($user) {
                        $projectQuery->where('manager_id', $user->id);
                    });
            });
        }

        return $notesQuery->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get notes statistics
     */
    public function getNotesStats($taskId = null)
    {
        $user = Auth::user();

        $notesQuery = TaskNote::query();

        if ($taskId) {
            $task = Task::findOrFail($taskId);
            $this->authorizeTaskAccess($task);
            $notesQuery->where('task_id', $taskId);
        } elseif ($user->role !== 'admin') {
            $notesQuery->whereHas('task', function ($query) use ($user) {
                $query->where('assigned_to', $user->id)
                    ->orWhereHas('project', function ($projectQuery) use ($user) {
                        $projectQuery->where('manager_id', $user->id);
                    });
            });
        }

        return [
            'total_notes' => $notesQuery->count(),
            'internal_notes' => (clone $notesQuery)->where('is_internal', true)->count(),
            'public_notes' => (clone $notesQuery)->where('is_internal', false)->count(),
            'notes_today' => (clone $notesQuery)->whereDate('created_at', today())->count(),
            'notes_this_week' => (clone $notesQuery)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];
    }

    /**
     * Check if user has access to the task with organization filtering
     */
    private function authorizeTaskAccess(Task $task)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin can access any task in their organization
            if ($task->project->organization_id !== $user->organization_id) {
                throw new ModelNotFoundException('Task not found or access denied.');
            }
            return true;
        }

        // Check if user is assigned to the task
        if ($task->assigned_to === $user->id) {
            return true;
        }

        // Check if user is the project manager
        if ($task->project && $task->project->manager_id === $user->id) {
            return true;
        }

        // Check organization access
        if ($task->project->organization_id !== $user->organization_id) {
            throw new ModelNotFoundException('Task not found or access denied.');
        }

        throw new ModelNotFoundException('Task not found or access denied.');
    }

    /**
     * Check if user can modify the note (author or admin)
     */
    private function authorizeNoteModification(TaskNote $note)
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $note->user_id === $user->id) {
            return true;
        }

        throw new ModelNotFoundException('Note not found or access denied.');
    }

    /**
     * Bulk create notes (useful for importing or batch operations)
     */
    public function bulkCreateNotes(array $notesData)
    {
        $createdNotes = [];

        foreach ($notesData as $noteData) {
            try {
                $createdNotes[] = $this->createNote($noteData['task_id'], $noteData);
            } catch (\Exception $e) {
                // Log error but continue with other notes
                \Log::error('Failed to create note: '.$e->getMessage(), $noteData);
            }
        }

        return $createdNotes;
    }

    /**
     * Export notes for a task or project
     */
    public function exportNotes($taskId = null, $format = 'array')
    {
        $notes = $this->getTaskNotes($taskId);

        $exportData = $notes->map(function ($note) {
            return [
                'id' => $note->id,
                'content' => $note->content,
                'is_internal' => $note->is_internal,
                'author' => $note->user->name,
                'created_at' => $note->created_at->toISOString(),
                'task_title' => $note->task->title,
            ];
        });

        if ($format === 'json') {
            return $exportData->toJson();
        }

        return $exportData->toArray();
    }

    /**
     * Create a status change note
     */
    public function createStatusChangeNote($taskId, string $oldStatus, string $newStatus, ?string $comment = null)
    {
        $task = Task::findOrFail($taskId);
        $this->authorizeTaskAccess($task);

        $noteData = [
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'content' => $comment ?: "Status changed from {$oldStatus} to {$newStatus}",
            'type' => 'status_change',
            'is_internal' => false,
            'metadata' => [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        ];

        $note = TaskNote::create($noteData);
        $this->notifyTaskStakeholders($note);

        return $note;
    }

    /**
     * Get task stakeholders for notifications
     */
    protected function getTaskStakeholders(Task $task): array
    {
        $stakeholders = [];

        // Add task assignee
        if ($task->assigned_to) {
            $stakeholders[] = $task->assigned_to;
        }

        // Add project manager
        if ($task->project && $task->project->manager_id) {
            $stakeholders[] = $task->project->manager_id;
        }

        // Add organization admins
        $admins = User::where('role', 'admin')
            ->where('organization_id', $task->project->organization_id)
            ->pluck('id')
            ->toArray();

        $stakeholders = array_merge($stakeholders, $admins);

        // Remove current user and duplicates
        $stakeholders = array_unique($stakeholders);
        $stakeholders = array_filter($stakeholders, function($id) {
            return $id !== Auth::id();
        });

        return $stakeholders;
    }

    /**
     * Send notifications to task stakeholders
     */
    protected function notifyTaskStakeholders(TaskNote $note): void
    {
        $stakeholders = $this->getTaskStakeholders($note->task);

        foreach ($stakeholders as $userId) {
            // Skip internal notes for non-managers/admins
            if ($note->is_internal) {
                $user = User::find($userId);
                if (!$user || (!$user->isAdmin() && !$user->isManager())) {
                    continue;
                }
            }

            // Create notification
            $user = User::find($userId);
            if ($user) {
                Notification::createTaskNoteNotification($user, $note);
            }
        }
    }

    /**
     * Create intervention note (manager/admin commenting on task progress)
     */
    public function createInterventionNote($taskId, string $content, bool $isInternal = false, array $files = [])
    {
        $user = Auth::user();

        // Only managers and admins can create intervention notes
        if (!$user->isAdmin() && !$user->isManager()) {
            throw new \UnauthorizedHttpException('Unauthorized to create intervention notes');
        }

        return $this->createNote($taskId, [
            'content' => $content,
            'type' => 'intervention',
            'is_internal' => $isInternal,
            'metadata' => [
                'intervention_type' => 'manual',
                'user_role' => $user->role,
            ],
        ], $files);
    }

    /**
     * Get notes with organization filtering
     */
    public function getTaskNotesWithOrgFilter($taskId, User $user)
    {
        $task = Task::findOrFail($taskId);
        $this->authorizeTaskAccess($task);

        $query = TaskNote::where('task_id', $taskId)->with(['user']);

        // Filter based on user role and internal notes
        if (!$user->isAdmin() && !$user->isManager()) {
            $query->where('is_internal', false);
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

}
