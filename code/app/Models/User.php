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
     * Get all projects user has access to (managed or has assigned tasks)
     */
    public function accessibleProjects()
    {
        if ($this->isAdmin()) {
            return Project::query();
        }

        if ($this->isManager()) {
            return $this->managedProjects();
        }

        // For members, get projects where they have assigned tasks
        return Project::whereHas('tasks', function ($query) {
            $query->where('assigned_to', $this->id);
        });
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
     * Check if user can view project (based on specific project)
     */
    public function canViewProject(Project $project): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isManager() && $project->manager_id === $this->id) {
            return true;
        }

        // Check if user has any assigned tasks in this project
        return $project->tasks()->where('assigned_to', $this->id)->exists();
    }

    /**
     * Check if user can edit project
     */
    public function canEditProject(Project $project): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->isManager() && $project->manager_id === $this->id;
    }

    /**
     * Check if user can view specific task
     */
    public function canViewTask(Task $task): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isManager() && $task->project->manager_id === $this->id) {
            return true;
        }

        return $task->assigned_to === $this->id;
    }

    /**
     * Check if user can edit specific task
     */
    public function canEditTask(Task $task): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isManager() && $task->project->manager_id === $this->id) {
            return true;
        }

        // Task assignee can edit their own tasks
        return $task->assigned_to === $this->id;
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
