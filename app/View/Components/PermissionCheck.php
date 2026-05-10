<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class PermissionCheck extends Component
{
    public string $permission;
    public $resource;

    /**
     * Create a new component instance.
     */
    public function __construct(string $permission, $resource = null)
    {
        $this->permission = $permission;
        $this->resource = $resource;
    }

    /**
     * Determine if the view should be rendered.
     */
    public function shouldRender(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        switch ($this->permission) {
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
                if ($this->resource) {
                    return $user->canViewProject($this->resource);
                }
                return $user->canManageProjects();

            case 'edit.project':
                if ($this->resource) {
                    return $user->canEditProject($this->resource);
                }
                return $user->canManageProjects();

            case 'view.task':
                if ($this->resource) {
                    return $user->canViewTask($this->resource);
                }
                return $user->canWorkOnTasks();

            case 'edit.task':
                if ($this->resource) {
                    return $user->canEditTask($this->resource);
                }
                return $user->canWorkOnTasks();

            default:
                return false;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return $this->shouldRender() ? $this->view('components.permission-check') : null;
    }
}