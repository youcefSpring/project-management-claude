<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrganizationService
{
    /**
     * Create a new organization
     */
    public function create(array $data): Organization
    {
        return Organization::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'website' => $data['website'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'logo' => $data['logo'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update organization
     */
    public function update(Organization $organization, array $data): bool
    {
        return $organization->update($data);
    }

    /**
     * Add user to organization
     */
    public function addUser(Organization $organization, User $user, string $role = User::ROLE_MEMBER): bool
    {
        if ($user->organization_id && $user->organization_id !== $organization->id) {
            throw ValidationException::withMessages([
                'user' => ['User already belongs to another organization'],
            ]);
        }

        return $user->update([
            'organization_id' => $organization->id,
            'role' => $role,
        ]);
    }

    /**
     * Remove user from organization
     */
    public function removeUser(Organization $organization, User $user): bool
    {
        if ($user->organization_id !== $organization->id) {
            throw ValidationException::withMessages([
                'user' => ['User does not belong to this organization'],
            ]);
        }

        // Don't allow removing the last admin
        if ($user->isAdmin() && $this->countAdmins($organization) <= 1) {
            throw ValidationException::withMessages([
                'user' => ['Cannot remove the last admin from the organization'],
            ]);
        }

        return $user->update(['organization_id' => null]);
    }

    /**
     * Count admin users in organization
     */
    public function countAdmins(Organization $organization): int
    {
        return $organization->users()->where('role', User::ROLE_ADMIN)->count();
    }

    /**
     * Get organization statistics
     */
    public function getStatistics(Organization $organization): array
    {
        return [
            'total_users' => $organization->users()->count(),
            'total_projects' => $organization->projects()->count(),
            'active_projects' => $organization->projects()->where('status', '!=', 'completed')->count(),
            'completed_projects' => $organization->projects()->where('status', 'completed')->count(),
            'admins_count' => $organization->users()->where('role', User::ROLE_ADMIN)->count(),
            'managers_count' => $organization->users()->where('role', User::ROLE_MANAGER)->count(),
            'members_count' => $organization->users()->whereNotIn('role', [User::ROLE_ADMIN, User::ROLE_MANAGER])->count(),
        ];
    }

    /**
     * Deactivate organization
     */
    public function deactivate(Organization $organization): bool
    {
        return $organization->update(['is_active' => false]);
    }

    /**
     * Activate organization
     */
    public function activate(Organization $organization): bool
    {
        return $organization->update(['is_active' => true]);
    }

    /**
     * Delete organization (soft delete)
     */
    public function delete(Organization $organization): bool
    {
        return DB::transaction(function () use ($organization) {
            // First deactivate
            $organization->update(['is_active' => false]);

            // Soft delete the organization
            return $organization->delete();
        });
    }

    /**
     * Transfer organization ownership
     */
    public function transferOwnership(Organization $organization, User $currentOwner, User $newOwner): bool
    {
        if ($newOwner->organization_id !== $organization->id) {
            throw ValidationException::withMessages([
                'new_owner' => ['New owner must be a member of this organization'],
            ]);
        }

        return DB::transaction(function () use ($currentOwner, $newOwner) {
            // Make new owner an admin
            $newOwner->update(['role' => User::ROLE_ADMIN]);

            // Optionally demote current owner to manager
            $currentOwner->update(['role' => User::ROLE_MANAGER]);

            return true;
        });
    }

    /**
     * Get users available for invitation (users without organization)
     */
    public function getAvailableUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::whereNull('organization_id')->orderBy('name')->get();
    }

    /**
     * Invite user to organization via email
     */
    public function inviteUser(Organization $organization, string $email, string $role = User::ROLE_MEMBER): array
    {
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            if ($existingUser->organization_id) {
                throw ValidationException::withMessages([
                    'email' => ['User already belongs to an organization'],
                ]);
            }

            // Add existing user to organization
            $this->addUser($organization, $existingUser, $role);

            return [
                'success' => true,
                'message' => 'User added to organization',
                'user' => $existingUser,
            ];
        }

        // For new users, you might want to create an invitation system
        // For now, just return that an invitation would be sent
        return [
            'success' => true,
            'message' => 'Invitation would be sent to ' . $email,
            'invitation_pending' => true,
        ];
    }

    /**
     * Check if user can manage organization
     */
    public function canManage(User $user, Organization $organization): bool
    {
        return $user->organization_id === $organization->id && $user->isAdmin();
    }

    /**
     * Check if user can view organization
     */
    public function canView(User $user, Organization $organization): bool
    {
        return $user->organization_id === $organization->id;
    }
}