<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_project(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/projects', [
                'title' => 'Test Project',
                'description' => 'A project created by test',
                'deadline' => date('Y-m-d'),
            ]);

        $response->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'title' => 'Test Project',
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        // create a project belonging to the user
        $project = Project::create([
            'title' => 'Owner Project',
            'description' => null,
            'deadline' => date('Y-m-d'),
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/tasks', [
                'title' => 'Test Task',
                'status' => 'todo',
                'due_date' => null,
                'project_id' => $project->id,
            ]);

        $response->assertRedirect(route('projects.show', $project->id));

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'project_id' => $project->id,
        ]);
    }

    public function test_guest_is_redirected_from_protected_routes(): void
    {
        // trying to access index of projects should redirect to login
        $this->get('/projects')->assertRedirect('/login');

        // trying to post to create a project should also redirect to login
        $this->post('/projects', [])->assertRedirect('/login');
    }
}
