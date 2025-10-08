<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check each permission
        foreach ($permissions as $permission) {
            if (! $this->userHasPermission($user, $permission, $request)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Access denied. Insufficient permissions.',
                        'required_permission' => $permission,
                        'user_role' => $user->role,
                    ], 403);
                }

                abort(403, 'Access denied. You do not have the required permissions.');
            }
        }

        return $next($request);
    }

    /**
     * Check if user has specific permission
     */
    private function userHasPermission($user, string $permission, Request $request): bool
    {
        switch ($permission) {
            case 'view.users':
                return $user->canViewUsers();

            case 'manage.users':
                return $user->canManageUsers();

            case 'view.reports':
                return $user->canViewReports();

            case 'view.financial.reports':
                return $user->canViewFinancialReports();

            case 'manage.projects':
                return $user->canManageProjects();

            case 'delete.projects':
                return $user->canDeleteProjects();

            case 'assign.tasks':
                return $user->canAssignTasks();

            case 'view.timetracking':
                return $user->canViewTimeTracking();

            case 'log.time.others':
                return $user->canLogTimeForOthers();

            case 'access.admin.dashboard':
                return $user->canAccessAdminDashboard();

            case 'access.manager.dashboard':
                return $user->canAccessManagerDashboard();

            case 'view.project':
                $projectId = $request->route('project')?->id ?? $request->get('project_id');
                if ($projectId) {
                    $project = \App\Models\Project::find($projectId);
                    return $project && $user->canViewProject($project);
                }
                return $user->canManageProjects();

            case 'edit.project':
                $projectId = $request->route('project')?->id ?? $request->get('project_id');
                if ($projectId) {
                    $project = \App\Models\Project::find($projectId);
                    return $project && $user->canEditProject($project);
                }
                return $user->canManageProjects();

            case 'view.task':
                $taskId = $request->route('task')?->id ?? $request->get('task_id');
                if ($taskId) {
                    $task = \App\Models\Task::find($taskId);
                    return $task && $user->canViewTask($task);
                }
                return $user->canWorkOnTasks();

            case 'edit.task':
                $taskId = $request->route('task')?->id ?? $request->get('task_id');
                if ($taskId) {
                    $task = \App\Models\Task::find($taskId);
                    return $task && $user->canEditTask($task);
                }
                return $user->canWorkOnTasks();

            default:
                return false;
        }
    }
}
