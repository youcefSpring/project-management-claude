<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskNote;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskNoteService
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function getTaskNotes($taskId)
    {
        $task = Task::findOrFail($taskId);

        return TaskNote::where('task_id', $taskId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createNote($taskId, array $data, array $files = [])
    {
        $task = Task::findOrFail($taskId);

        $attachments = [];
        if (! empty($files)) {
            foreach ($files as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $attachments[] = $this->imageService->uploadAndResize($file);
                }
            }
        }

        $note = TaskNote::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'content' => $data['content'] ?? '',
            'is_internal' => $data['is_internal'] ?? false,
            'type' => $data['type'] ?? (! empty($attachments) ? 'attachment' : 'comment'),
            'attachments' => ! empty($attachments) ? $attachments : null,
            'metadata' => $data['metadata'] ?? null,
        ]);

        $this->notifyTaskStakeholders($note);

        return $note;
    }

    public function createStatusChangeNote($taskId, string $oldStatus, string $newStatus, ?string $comment = null)
    {
        $task = Task::findOrFail($taskId);

        $note = TaskNote::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'content' => $comment ?: "Status changed from {$oldStatus} to {$newStatus}",
            'type' => 'status_change',
            'is_internal' => false,
            'metadata' => json_encode([
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]),
        ]);

        $this->notifyTaskStakeholders($note);

        return $note;
    }

    protected function notifyTaskStakeholders(TaskNote $note)
    {
        $task = $note->task;
        $project = $task->project;

        $users = User::where('organization_id', $project->organization_id)
            ->where('id', '!=', $note->user_id)
            ->whereIn('role', ['admin', 'manager'])
            ->get();

        foreach ($users as $user) {
            $user->notify(new \App\Notifications\NewTaskComment($note));
        }
    }
}
