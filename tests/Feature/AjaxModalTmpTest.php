<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class AjaxModalTmpTest extends TestCase
{
    private function admin(): User
    {
        return User::whereIn('role', ['admin', 'manager'])->first() ?? User::first();
    }

    public function test_create_page_returns_a_form(): void
    {
        $this->actingAs($this->admin())
            ->get(route('tasks.create'))
            ->assertOk()
            ->assertSee('<form', false);
    }

    public function test_store_returns_json_on_xhr(): void
    {
        $project = Project::first();
        $res = $this->actingAs($this->admin())
            ->postJson(route('tasks.store'), [
                'title' => 'AJAX engine test task',
                'project_id' => $project->id,
                'priority' => 'medium',
            ]);
        $res->assertOk()->assertJson(['success' => true]);
        $this->assertNotNull($res->json('redirect'));

        // cleanup
        Task::where('title', 'AJAX engine test task')->delete();
    }

    public function test_validation_returns_422_json(): void
    {
        $this->actingAs($this->admin())
            ->postJson(route('tasks.store'), ['title' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'project_id', 'priority']);
    }

    public function test_destroy_returns_json(): void
    {
        $project = Project::first();
        $task = Task::create([
            'title' => 'AJAX delete test',
            'project_id' => $project->id,
            'priority' => 'low',
            'status' => 'pending',
            'created_by' => $this->admin()->id,
        ]);

        $this->actingAs($this->admin())
            ->deleteJson(route('tasks.destroy', $task))
            ->assertOk()
            ->assertJson(['success' => true]);
    }
}
