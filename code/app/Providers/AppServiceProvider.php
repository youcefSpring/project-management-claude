<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Register custom Blade directives for permissions
        Blade::if('hasPermission', function ($permission, $resource = null) {
            if (!auth()->check()) {
                return false;
            }

            $user = auth()->user();

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
                    if ($resource) {
                        return $user->canViewProject($resource);
                    }
                    return $user->canManageProjects();

                case 'edit.project':
                    if ($resource) {
                        return $user->canEditProject($resource);
                    }
                    return $user->canManageProjects();

                case 'view.task':
                    if ($resource) {
                        return $user->canViewTask($resource);
                    }
                    return $user->canWorkOnTasks();

                case 'edit.task':
                    if ($resource) {
                        return $user->canEditTask($resource);
                    }
                    return $user->canWorkOnTasks();

                default:
                    return false;
            }
        });

        // Register role-based Blade directives
        Blade::if('hasRole', function ($roles) {
            if (!auth()->check()) {
                return false;
            }

            $userRoles = is_array($roles) ? $roles : [$roles];
            return auth()->user()->hasAnyRole($userRoles);
        });

        Blade::if('isAdmin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        Blade::if('isManager', function () {
            return auth()->check() && auth()->user()->isManager();
        });

        Blade::if('canWorkOnTasks', function () {
            return auth()->check() && auth()->user()->canWorkOnTasks();
        });
    }
}
