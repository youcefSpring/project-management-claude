<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskNote;
use App\Services\TaskNoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskNoteController extends Controller
{
    protected TaskNoteService $taskNoteService;

    public function __construct(TaskNoteService $taskNoteService)
    {
        $this->taskNoteService = $taskNoteService;
    }

    /**
     * Store a new task note with attachments
     */
    public function store(Request $request, Task $task)
    {
        $user = Auth::user();

        // Check if user can view the task
        if (! $task->canBeViewedBy($user)) {
            abort(403, __('app.comments.no_permission_add_notes'));
        }

        $request->validate([
            'content' => 'nullable|string|max:1000',
            'type' => 'nullable|string|in:comment,intervention,attachment',
            'is_internal' => 'boolean',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max per image
        ]);

        // Validate that either content or attachments are provided
        if (empty($request->content) && !$request->hasFile('attachments')) {
            return redirect()->back()
                ->withErrors(['content' => __('app.notes.content_or_attachments_required')])
                ->withInput();
        }

        $data = [
            'content' => $request->content,
            'type' => $request->type ?? 'comment',
            'is_internal' => $request->boolean('is_internal', false),
        ];

        $files = $request->file('attachments', []);

        try {
            $note = $this->taskNoteService->createNote($task->id, $data, $files);

            $message = match($note->type) {
                'intervention' => __('app.notes.intervention_added_successfully'),
                'attachment' => __('app.notes.attachment_added_successfully'),
                default => __('app.comments.comment_added_successfully')
            };

            return redirect()->route('tasks.show', $task)
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update a task note
     */
    public function update(Request $request, TaskNote $note)
    {
        $user = Auth::user();

        // Check if user can edit this note
        if (! $note->canBeEditedBy($user)) {
            abort(403, __('app.comments.no_permission_edit_note'));
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $note->update([
            'content' => $request->content,
        ]);

        return redirect()->route('tasks.show', $note->task)
            ->with('success', __('app.comments.comment_updated_successfully'));
    }

    /**
     * Delete a task note
     */
    public function destroy(TaskNote $note)
    {
        $user = Auth::user();

        // Check if user can delete this note
        if (! $note->canBeDeletedBy($user)) {
            abort(403, __('app.comments.no_permission_delete_note'));
        }

        $task = $note->task;
        $note->delete();

        return redirect()->route('tasks.show', $task)
            ->with('success', __('app.comments.comment_deleted_successfully'));
    }
}
