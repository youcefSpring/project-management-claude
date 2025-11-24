<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectNoteController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $user = Auth::user();

        if (! $project->canBeViewedBy($user)) {
            abort(403, __('app.comments.no_permission_add_notes'));
        }

        $request->validate([
            'content' => 'nullable|string|max:1000',
            'type' => 'nullable|string|in:comment,attachment',
            'is_internal' => 'boolean',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if (empty($request->content) && !$request->hasFile('attachments')) {
            return redirect()->back()
                ->withErrors(['content' => __('app.notes.content_or_attachments_required')])
                ->withInput();
        }

        $attachments = [];
        if ($request->hasFile('attachments')) {
            $imageService = app(\App\Services\ImageService::class);
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $imageService->uploadImage($file, 'project-notes');
            }
        }

        ProjectNote::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'content' => $request->content,
            'type' => $request->type ?? 'comment',
            'is_internal' => $request->boolean('is_internal', false),
            'attachments' => !empty($attachments) ? $attachments : null,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', __('app.comments.comment_added_successfully'));
    }

    public function destroy(ProjectNote $note)
    {
        $user = Auth::user();

        if (! $note->canBeDeletedBy($user)) {
            abort(403, __('app.comments.no_permission_delete_note'));
        }

        $project = $note->project;
        $note->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', __('app.comments.comment_deleted_successfully'));
    }
}
