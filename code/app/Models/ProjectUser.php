<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectUser extends Model
{
    use HasFactory;

    protected $table = 'project_user';

    protected $fillable = [
        'project_id',
        'user_id',
        'roles',
        'is_active',
        'joined_at',
        'left_at',
    ];

    protected $casts = [
        'roles' => 'array',
        'is_active' => 'boolean',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    /**
     * Define project role constants
     */
    const ROLE_MANAGER = 'manager';
    const ROLE_DEVELOPER = 'developer';
    const ROLE_DESIGNER = 'designer';
    const ROLE_TESTER = 'tester';
    const ROLE_CLIENT = 'client';
    const ROLE_VIEWER = 'viewer';

    /**
     * Get all available project roles
     */
    public static function getProjectRoles(): array
    {
        return [
            self::ROLE_MANAGER,
            self::ROLE_DEVELOPER,
            self::ROLE_DESIGNER,
            self::ROLE_TESTER,
            self::ROLE_CLIENT,
            self::ROLE_VIEWER,
        ];
    }

    /**
     * Get role labels for display
     */
    public static function getProjectRoleLabels(): array
    {
        return [
            self::ROLE_MANAGER => 'Project Manager',
            self::ROLE_DEVELOPER => 'Developer',
            self::ROLE_DESIGNER => 'Designer',
            self::ROLE_TESTER => 'QA Tester',
            self::ROLE_CLIENT => 'Client',
            self::ROLE_VIEWER => 'Viewer',
        ];
    }

    /**
     * Get the project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user has specific role in this project
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles ?? []);
    }

    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return !empty(array_intersect($roles, $this->roles ?? []));
    }

    /**
     * Add a role to the user for this project
     */
    public function addRole(string $role): void
    {
        $roles = $this->roles ?? [];
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $this->roles = $roles;
            $this->save();
        }
    }

    /**
     * Remove a role from the user for this project
     */
    public function removeRole(string $role): void
    {
        $roles = $this->roles ?? [];
        $roles = array_filter($roles, fn($r) => $r !== $role);
        $this->roles = array_values($roles);
        $this->save();
    }

    /**
     * Set roles for the user in this project
     */
    public function setRoles(array $roles): void
    {
        $this->roles = array_values(array_unique($roles));
        $this->save();
    }

    /**
     * Check if user is project manager
     */
    public function isProjectManager(): bool
    {
        return $this->hasRole(self::ROLE_MANAGER);
    }

    /**
     * Check if user is developer in this project
     */
    public function isDeveloper(): bool
    {
        return $this->hasRole(self::ROLE_DEVELOPER);
    }

    /**
     * Check if user is designer in this project
     */
    public function isDesigner(): bool
    {
        return $this->hasRole(self::ROLE_DESIGNER);
    }

    /**
     * Check if user is tester in this project
     */
    public function isTester(): bool
    {
        return $this->hasRole(self::ROLE_TESTER);
    }

    /**
     * Check if user is client in this project
     */
    public function isClient(): bool
    {
        return $this->hasRole(self::ROLE_CLIENT);
    }

    /**
     * Check if user can work on tasks in this project
     */
    public function canWorkOnTasks(): bool
    {
        return $this->hasAnyRole([
            self::ROLE_MANAGER,
            self::ROLE_DEVELOPER,
            self::ROLE_DESIGNER,
            self::ROLE_TESTER,
        ]);
    }

    /**
     * Check if user can manage this project
     */
    public function canManageProject(): bool
    {
        return $this->hasRole(self::ROLE_MANAGER);
    }

    /**
     * Scope to filter active memberships
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by project
     */
    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope to filter by user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by role
     */
    public function scopeWithRole($query, string $role)
    {
        return $query->whereJsonContains('roles', $role);
    }
}
