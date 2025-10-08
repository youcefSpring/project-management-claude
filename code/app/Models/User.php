<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'language',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define role constants
     */
    const ROLE_ADMIN = 'admin';

    const ROLE_MANAGER = 'manager';

    const ROLE_DEVELOPER = 'developer';

    const ROLE_DESIGNER = 'designer';

    const ROLE_TESTER = 'tester';

    const ROLE_HR = 'hr';

    const ROLE_ACCOUNTANT = 'accountant';

    const ROLE_CLIENT = 'client';

    const ROLE_MEMBER = 'member'; // Keep for backward compatibility

    /**
     * Get all available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_MANAGER,
            self::ROLE_DEVELOPER,
            self::ROLE_DESIGNER,
            self::ROLE_TESTER,
            self::ROLE_HR,
            self::ROLE_ACCOUNTANT,
            self::ROLE_CLIENT,
            self::ROLE_MEMBER,
        ];
    }

    /**
     * Get role labels for display
     */
    public static function getRoleLabels(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_MANAGER => 'Project Manager',
            self::ROLE_DEVELOPER => 'Developer',
            self::ROLE_DESIGNER => 'Designer',
            self::ROLE_TESTER => 'QA Tester',
            self::ROLE_HR => 'Human Resources',
            self::ROLE_ACCOUNTANT => 'Accountant',
            self::ROLE_CLIENT => 'Client',
            self::ROLE_MEMBER => 'Member',
        ];
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    /**
     * Check if user is manager
     */
    public function isManager(): bool
    {
        return $this->hasRole(self::ROLE_MANAGER);
    }

    /**
     * Check if user is member
     */
    public function isMember(): bool
    {
        return $this->hasRole(self::ROLE_MEMBER);
    }

    /**
     * Check if user is developer
     */
    public function isDeveloper(): bool
    {
        return $this->hasRole(self::ROLE_DEVELOPER);
    }

    /**
     * Check if user is designer
     */
    public function isDesigner(): bool
    {
        return $this->hasRole(self::ROLE_DESIGNER);
    }

    /**
     * Check if user is tester
     */
    public function isTester(): bool
    {
        return $this->hasRole(self::ROLE_TESTER);
    }

    /**
     * Check if user is HR
     */
    public function isHR(): bool
    {
        return $this->hasRole(self::ROLE_HR);
    }

    /**
     * Check if user is accountant
     */
    public function isAccountant(): bool
    {
        return $this->hasRole(self::ROLE_ACCOUNTANT);
    }

    /**
     * Check if user is client
     */
    public function isClient(): bool
    {
        return $this->hasRole(self::ROLE_CLIENT);
    }

    /**
     * Check if user can manage projects (admin or manager)
     */
    public function canManageProjects(): bool
    {
        return $this->isAdmin() || $this->isManager();
    }

    /**
     * Check if user can work on tasks (not just admin/manager)
     */
    public function canWorkOnTasks(): bool
    {
        return ! $this->isClient() && ! $this->isHR() && ! $this->isAccountant();
    }

    /**
     * Get user's role label
     */
    public function getRoleLabel(): string
    {
        $labels = self::getRoleLabels();

        return $labels[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Get projects where user is manager
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    /**
     * Get tasks assigned to user
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Get time entries created by user
     */
    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * Get notes created by user
     */
    public function taskNotes()
    {
        return $this->hasMany(TaskNote::class);
    }

    /**
     * Get project memberships with roles
     */
    public function projectMemberships()
    {
        return $this->hasMany(ProjectUser::class);
    }

    /**
     * Get active project memberships
     */
    public function activeProjectMemberships()
    {
        return $this->projectMemberships()->active();
    }

    /**
     * Get projects where user is a member (through project_user pivot)
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->using(ProjectUser::class)
            ->withPivot(['roles', 'is_active', 'joined_at', 'left_at'])
            ->withTimestamps()
            ->wherePivot('is_active', true);
    }

    /**
     * Get all projects user has access to (through project membership or admin)
     */
    public function accessibleProjects()
    {
        if ($this->isAdmin()) {
            return Project::query();
        }

        // Get projects where user is a member
        return $this->projects();
    }

    /**
     * Get projects where user has a specific role
     */
    public function projectsWithRole(string $role)
    {
        return $this->projects()->whereJsonContains('project_user.roles', $role);
    }

    /**
     * Get projects where user is a manager
     */
    public function managedProjectsViaRole()
    {
        return $this->projectsWithRole(ProjectUser::ROLE_MANAGER);
    }

    /**
     * Check if user has specific role in a project
     */
    public function hasRoleInProject(Project $project, string $role): bool
    {
        if ($this->isAdmin()) {
            return true; // Admin has all roles
        }

        $membership = $this->projectMemberships()
            ->forProject($project->id)
            ->active()
            ->first();

        return $membership && $membership->hasRole($role);
    }

    /**
     * Check if user has any of the specified roles in a project
     */
    public function hasAnyRoleInProject(Project $project, array $roles): bool
    {
        if ($this->isAdmin()) {
            return true; // Admin has all roles
        }

        $membership = $this->projectMemberships()
            ->forProject($project->id)
            ->active()
            ->first();

        return $membership && $membership->hasAnyRole($roles);
    }

    /**
     * Get user's roles in a specific project
     */
    public function getRolesInProject(Project $project): array
    {
        if ($this->isAdmin()) {
            return ProjectUser::getProjectRoles(); // Admin has all roles
        }

        $membership = $this->projectMemberships()
            ->forProject($project->id)
            ->active()
            ->first();

        return $membership ? $membership->roles : [];
    }

    /**
     * Check if user is manager of a specific project
     */
    public function isManagerOfProject(Project $project): bool
    {
        return $this->hasRoleInProject($project, ProjectUser::ROLE_MANAGER);
    }

    /**
     * Check if user is developer in a specific project
     */
    public function isDeveloperInProject(Project $project): bool
    {
        return $this->hasRoleInProject($project, ProjectUser::ROLE_DEVELOPER);
    }

    /**
     * Check if user can work on tasks in a specific project
     */
    public function canWorkOnTasksInProject(Project $project): bool
    {
        return $this->hasAnyRoleInProject($project, [
            ProjectUser::ROLE_MANAGER,
            ProjectUser::ROLE_DEVELOPER,
            ProjectUser::ROLE_DESIGNER,
            ProjectUser::ROLE_TESTER,
        ]);
    }

    /**
     * Check if user can view user management
     */
    public function canViewUsers(): bool
    {
        return $this->isAdmin() || $this->isHR();
    }

    /**
     * Check if user can create/edit users
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can view reports
     */
    public function canViewReports(): bool
    {
        return $this->isAdmin() || $this->isManager() || $this->isAccountant();
    }

    /**
     * Check if user can view financial reports
     */
    public function canViewFinancialReports(): bool
    {
        return $this->isAdmin() || $this->isAccountant();
    }

    /**
     * Check if user can view time tracking
     */
    public function canViewTimeTracking(): bool
    {
        return $this->canWorkOnTasks() || $this->isManager() || $this->isAdmin();
    }

    /**
     * Check if user can log time for others
     */
    public function canLogTimeForOthers(): bool
    {
        return $this->isAdmin() || $this->isManager();
    }

    /**
     * Check if user can delete projects
     */
    public function canDeleteProjects(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can assign tasks to others
     */
    public function canAssignTasks(): bool
    {
        return $this->isAdmin() || $this->isManager();
    }

    /**
     * Check if user can view project (based on project membership)
     */
    public function canViewProject(Project $project): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Check if user has any role in the project
        $membership = $this->projectMemberships()
            ->forProject($project->id)
            ->active()
            ->first();

        if ($membership !== null) {
            return true;
        }

        // Legacy support: check if user is the project manager
        if ($project->manager_id === $this->id) {
            return true;
        }

        // Fallback: check if user has assigned tasks in this project
        return $this->assignedTasks()->where('project_id', $project->id)->exists();
    }

    /**
     * Check if user can edit project (manager role in project)
     */
    public function canEditProject(Project $project): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Check if user has manager role in the project
        if ($this->isManagerOfProject($project)) {
            return true;
        }

        // Legacy support: check if user is the project manager
        return $project->manager_id === $this->id;
    }

    /**
     * Check if user can view specific task (based on project membership)
     */
    public function canViewTask(Task $task): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Check if user has any role in the project or is assigned to the task
        return $this->canViewProject($task->project) || $task->assigned_to === $this->id;
    }

    /**
     * Check if user can edit specific task (based on project role or assignment)
     */
    public function canEditTask(Task $task): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Manager of the project can edit all tasks
        if ($this->isManagerOfProject($task->project)) {
            return true;
        }

        // Task assignee can edit their own tasks if they can work on tasks in this project
        return $task->assigned_to === $this->id && $this->canWorkOnTasksInProject($task->project);
    }

    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user can access admin dashboard
     */
    public function canAccessAdminDashboard(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can access manager dashboard
     */
    public function canAccessManagerDashboard(): bool
    {
        return $this->isAdmin() || $this->isManager();
    }
}
