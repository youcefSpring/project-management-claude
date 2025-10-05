<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Authenticate user with email and password
     */
    public function authenticate(string $email, string $password, bool $remember = false): array
    {
        // Validate credentials
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user account is active (you could add a status field)
        if (! $this->isUserActive($user)) {
            throw new AuthenticationException('Your account has been deactivated.');
        }

        // Log the user in
        Auth::login($user, $remember);

        // Set user language in session
        $this->setUserLanguage($user->language);

        // Record login activity (optional)
        $this->recordLoginActivity($user);

        return [
            'user' => $user,
            'redirect_url' => $this->getDashboardUrl($user),
            'success' => true,
        ];
    }

    /**
     * Register a new user
     */
    public function register(array $userData): User
    {
        // Hash password
        $userData['password'] = Hash::make($userData['password']);

        // Set default language if not provided
        $userData['language'] = $userData['language'] ?? 'fr';

        // Set default role
        $userData['role'] = $userData['role'] ?? User::ROLE_MEMBER;

        // Create user
        $user = User::create($userData);

        // Automatically log in the new user
        Auth::login($user);

        // Set language
        $this->setUserLanguage($user->language);

        return $user;
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            $this->recordLogoutActivity($user);
        }

        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
    }

    /**
     * Change user password
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return true;
    }

    /**
     * Set user language preference
     */
    public function setUserLanguage(string $language): void
    {
        if (in_array($language, ['fr', 'en', 'ar'])) {
            Session::put('locale', $language);
            app()->setLocale($language);
        }
    }

    /**
     * Update user language preference
     */
    public function updateUserLanguage(User $user, string $language): bool
    {
        if (! in_array($language, ['fr', 'en', 'ar'])) {
            throw ValidationException::withMessages([
                'language' => ['The selected language is invalid.'],
            ]);
        }

        $user->update(['language' => $language]);
        $this->setUserLanguage($language);

        return true;
    }

    /**
     * Get dashboard URL based on user role
     */
    public function getDashboardUrl(User $user): string
    {
        return match ($user->role) {
            User::ROLE_ADMIN => '/admin/dashboard',
            User::ROLE_MANAGER => '/manager/dashboard',
            User::ROLE_MEMBER => '/dashboard',
            default => '/dashboard',
        };
    }

    /**
     * Check if user can access specific role area
     */
    public function canAccessRole(User $user, string $requiredRole): bool
    {
        $roleHierarchy = [
            User::ROLE_ADMIN => 3,
            User::ROLE_MANAGER => 2,
            User::ROLE_MEMBER => 1,
        ];

        $userLevel = $roleHierarchy[$user->role] ?? 0;
        $requiredLevel = $roleHierarchy[$requiredRole] ?? 0;

        return $userLevel >= $requiredLevel;
    }

    /**
     * Check if user account is active
     */
    public function isUserActive(User $user): bool
    {
        // For now, all users are active
        // You could add a 'status' or 'is_active' field to users table
        return true;
    }

    /**
     * Get user permissions based on role
     */
    public function getUserPermissions(User $user): array
    {
        return match ($user->role) {
            User::ROLE_ADMIN => [
                'view_all_projects',
                'create_project',
                'edit_project',
                'delete_project',
                'manage_users',
                'view_all_reports',
                'manage_settings',
            ],
            User::ROLE_MANAGER => [
                'create_project',
                'edit_own_projects',
                'view_team_reports',
                'assign_tasks',
                'manage_team_time',
            ],
            User::ROLE_MEMBER => [
                'view_assigned_tasks',
                'update_task_status',
                'log_time',
                'add_notes',
            ],
            default => [],
        };
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(User $user, string $permission): bool
    {
        return in_array($permission, $this->getUserPermissions($user));
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $role): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('role', $role)->get();
    }

    /**
     * Get managers for project assignment
     */
    public function getAvailableManagers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_MANAGER])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get members for task assignment
     */
    public function getAvailableMembers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('role', User::ROLE_MEMBER)
            ->orderBy('name')
            ->get();
    }

    /**
     * Validate role transition (if changing user roles)
     */
    public function canChangeUserRole(User $currentUser, User $targetUser, string $newRole): bool
    {
        // Only admin can change roles
        if (! $currentUser->isAdmin()) {
            return false;
        }

        // Cannot change own role
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        // Validate role exists
        if (! in_array($newRole, User::getRoles())) {
            return false;
        }

        return true;
    }

    /**
     * Record login activity
     */
    private function recordLoginActivity(User $user): void
    {
        // Update last login timestamp if you have that field
        // $user->update(['last_login_at' => now()]);

        // Log activity (you could create an activity log table)
        \Log::info('User logged in', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Record logout activity
     */
    private function recordLogoutActivity(User $user): void
    {
        \Log::info('User logged out', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
        ]);
    }

    /**
     * Get user's active session info
     */
    public function getSessionInfo(User $user): array
    {
        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'language' => $user->language,
            'permissions' => $this->getUserPermissions($user),
            'session_id' => Session::getId(),
            'login_time' => Session::get('login_time', now()),
        ];
    }

    /**
     * Check session validity
     */
    public function isSessionValid(): bool
    {
        return Auth::check() && Session::has('login_time');
    }

    /**
     * Refresh user session
     */
    public function refreshSession(): void
    {
        if (Auth::check()) {
            Session::put('last_activity', now());
            Session::regenerate();
        }
    }
}
