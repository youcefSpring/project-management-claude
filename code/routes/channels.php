<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// User's private channel for notifications
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Project-specific channels
Broadcast::channel('project.{projectId}', function ($user, $projectId) {
    $project = App\Models\Project::find($projectId);

    if (! $project) {
        return false;
    }

    // Admin can access any project channel
    if ($user->isAdmin()) {
        return true;
    }

    // Manager can access their project channels
    if ($user->isManager() && $project->manager_id === $user->id) {
        return true;
    }

    // Members can access project channels if they have assigned tasks
    return $user->isMember() && $project->tasks()->where('assigned_to', $user->id)->exists();
});

// Task-specific channels for real-time updates
Broadcast::channel('task.{taskId}', function ($user, $taskId) {
    $task = App\Models\Task::find($taskId);

    if (! $task) {
        return false;
    }

    // Admin can access any task channel
    if ($user->isAdmin()) {
        return true;
    }

    // Manager can access tasks in their projects
    if ($user->isManager() && $task->project->manager_id === $user->id) {
        return true;
    }

    // Members can access their assigned task channels
    return $user->isMember() && $task->assigned_to === $user->id;
});

// Team collaboration channel for project teams
Broadcast::channel('team.{projectId}', function ($user, $projectId) {
    $project = App\Models\Project::find($projectId);

    if (! $project) {
        return false;
    }

    // Admin can access any team channel
    if ($user->isAdmin()) {
        return true;
    }

    // Manager can access their project team channels
    if ($user->isManager() && $project->manager_id === $user->id) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'role' => 'manager',
            'avatar' => $user->avatar ?? null,
        ];
    }

    // Members can access team channels if they have tasks in the project
    if ($user->isMember() && $project->tasks()->where('assigned_to', $user->id)->exists()) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'role' => 'member',
            'avatar' => $user->avatar ?? null,
        ];
    }

    return false;
});

// Global notifications channel (admin only)
Broadcast::channel('notifications.global', function ($user) {
    return $user->isAdmin() ? [
        'id' => $user->id,
        'name' => $user->name,
        'role' => 'admin',
    ] : false;
});

// Time tracking presence channel
Broadcast::channel('presence.time-tracking', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'role' => $user->role,
        'active_task' => $user->getActiveTask() ?? null,
    ];
});
