<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskNoteController extends Controller
{
    /**
     * Store a new task note
     */
    public function store(Request $request, Task $task)
    {
        $user = Auth::user();

        // Check if user can view the task
        if (! $task->canBeViewedBy($user)) {
            abort(403, __('app.comments.no_permission_add_notes'));
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $note = TaskNote::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'content' => $request->content,
        ]);

        return redirect()->route('tasks.show', $task)
            ->with('success', __('app.comments.comment_added_successfully'));
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
