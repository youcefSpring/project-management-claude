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
            abort(403, 'You do not have permission to add notes to this task.');
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
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Update a task note
     */
    public function update(Request $request, TaskNote $note)
    {
        $user = Auth::user();

        // Check if user can edit this note
        if (! $note->canBeEditedBy($user)) {
            abort(403, 'You do not have permission to edit this note.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $note->update([
            'content' => $request->content,
        ]);

        return redirect()->route('tasks.show', $note->task)
            ->with('success', 'Comment updated successfully.');
    }

    /**
     * Delete a task note
     */
    public function destroy(TaskNote $note)
    {
        $user = Auth::user();

        // Check if user can delete this note
        if (! $note->canBeDeletedBy($user)) {
            abort(403, 'You do not have permission to delete this note.');
        }

        $task = $note->task;
        $note->delete();

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Comment deleted successfully.');
    }
}
