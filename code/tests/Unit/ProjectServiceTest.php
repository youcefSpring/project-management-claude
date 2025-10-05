<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProjectService $projectService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectService = new ProjectService;
    }

    public function test_can_create_project(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $projectData = [
            'name' => 'Test Project',
            'description' => 'Test Description',
            'client_name' => 'Test Client',
            'budget' => 10000,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'priority' => 'medium',
        ];

        $project = $this->projectService->createProject($projectData);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Test Project', $project->name);
        $this->assertEquals($user->id, $project->created_by);
    }

    public function test_can_get_user_projects(): void
    {
        $user = User::factory()->create(['role' => 'member']);
        $this->actingAs($user);

        // Create projects
        Project::factory()->count(3)->create(['created_by' => $user->id]);
        Project::factory()->count(2)->create(); // Other user's projects

        $projects = $this->projectService->getUserProjects($user);

        $this->assertCount(3, $projects);
    }

    public function test_can_calculate_project_progress(): void
    {
        $project = Project::factory()->create();

        // This would require tasks to be created and completed
        $progress = $this->projectService->calculateProgress($project->id);

        $this->assertIsFloat($progress);
        $this->assertGreaterThanOrEqual(0, $progress);
        $this->assertLessThanOrEqual(100, $progress);
    }
}
