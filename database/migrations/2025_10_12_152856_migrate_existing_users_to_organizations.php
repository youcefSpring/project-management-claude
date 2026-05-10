<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Organization;
use App\Models\User;
use App\Models\Project;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create a legacy organization for existing users
        $legacyOrganization = Organization::create([
            'name' => 'Legacy Organization',
            'description' => 'Organization for existing users before multi-organization feature',
            'is_active' => true,
        ]);

        // Assign all existing users to the legacy organization
        User::whereNull('organization_id')->update([
            'organization_id' => $legacyOrganization->id
        ]);

        // Assign all existing projects to the legacy organization
        Project::whereNull('organization_id')->update([
            'organization_id' => $legacyOrganization->id
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove organization assignments
        User::where('organization_id', function ($query) {
            $query->select('id')
                ->from('organizations')
                ->where('name', 'Legacy Organization')
                ->limit(1);
        })->update(['organization_id' => null]);

        Project::where('organization_id', function ($query) {
            $query->select('id')
                ->from('organizations')
                ->where('name', 'Legacy Organization')
                ->limit(1);
        })->update(['organization_id' => null]);

        // Delete the legacy organization
        Organization::where('name', 'Legacy Organization')->delete();
    }
};
