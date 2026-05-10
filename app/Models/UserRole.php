<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
    const ROLE_MEMBER = 'member';

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
     * Get the user that owns this role
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get role label
     */
    public function getRoleLabel(): string
    {
        $labels = self::getRoleLabels();
        return $labels[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Scope to filter active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by role
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}