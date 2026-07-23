<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Default statuses created for every organization.
     */
    private array $defaults = [
        ['slug' => 'pending', 'name' => 'Pending', 'color' => '#ffc107', 'sort_order' => 1, 'is_default' => true, 'is_final' => false],
        ['slug' => 'in_progress', 'name' => 'In Progress', 'color' => '#0d6efd', 'sort_order' => 2, 'is_default' => false, 'is_final' => false],
        ['slug' => 'completed', 'name' => 'Completed', 'color' => '#198754', 'sort_order' => 3, 'is_default' => false, 'is_final' => true],
        ['slug' => 'cancelled', 'name' => 'Cancelled', 'color' => '#6c757d', 'sort_order' => 4, 'is_default' => false, 'is_final' => true],
    ];

    public function up(): void
    {
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->string('slug', 50);
            $table->string('name', 100);
            $table->string('color', 7)->default('#6c757d');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_final')->default(false);
            $table->timestamps();

            $table->unique(['organization_id', 'slug'], 'uniq_task_statuses_org_slug');
            $table->index(['organization_id', 'sort_order'], 'idx_task_statuses_org_order');
        });

        // tasks.status becomes a free-form slug referencing task_statuses.slug
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending'");

        $now = now();

        foreach (DB::table('organizations')->pluck('id') as $organizationId) {
            $rows = [];
            foreach ($this->defaults as $default) {
                $rows[] = $default + [
                    'organization_id' => $organizationId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('task_statuses')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('task_statuses');

        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
