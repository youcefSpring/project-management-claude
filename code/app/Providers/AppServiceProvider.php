<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Barryvdh\TranslationManager\Models\Translation;

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

        // Configure translation manager to load from database
        $this->app->singleton('translation.loader', function ($app) {
            return new \Barryvdh\TranslationManager\TranslationManager($app['files'], $app['path.lang']);
        });

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

        // URL Macro for localized routes
        URL::macro('localized', function ($name, $parameters = [], $locale = null) {
            $locale = $locale ?: app()->getLocale();
            $parameters = array_merge(['locale' => $locale], $parameters);
            return route($name, $parameters);
        });

        // View composer to inject locale into all views
        view()->composer('*', function ($view) {
            $view->with('currentLocale', app()->getLocale());
        });
    }
}
