<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Uses the query builder (not Eloquent) so it is independent of model
     * global scopes such as SoftDeletes, which add columns (deleted_at) that
     * may not exist yet at this point in the migration order.
     */
    public function up(): void
    {
        $legacyId = DB::table('organizations')->insertGetId([
            'name' => 'Legacy Organization',
            'description' => 'Organization for existing users before multi-organization feature',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->whereNull('organization_id')->update([
            'organization_id' => $legacyId,
            'updated_at' => now(),
        ]);

        DB::table('projects')->whereNull('organization_id')->update([
            'organization_id' => $legacyId,
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $legacyId = DB::table('organizations')->where('name', 'Legacy Organization')->value('id');

        if ($legacyId) {
            DB::table('users')->where('organization_id', $legacyId)->update(['organization_id' => null]);
            DB::table('projects')->where('organization_id', $legacyId)->update(['organization_id' => null]);
            DB::table('organizations')->where('id', $legacyId)->delete();
        }
    }
};
