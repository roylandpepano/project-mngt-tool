<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignedTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignee_can_update_status(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();

        $project = Project::create([
            'title' => 'Owner Project',
            'description' => null,
            'deadline' => date('Y-m-d'),
            'user_id' => $owner->id,
        ]);

        $task = Task::create([
            'title' => 'Assigned Task',
            'status' => 'in progress',
            'due_date' => null,
            'project_id' => $project->id,
            'assigned_to' => $assignee->id,
        ]);

        $response = $this
            ->actingAs($assignee)
            ->put(route('tasks.update', $task->id), [
                'status' => 'done',
            ]);

        // assignee update should redirect back (302)
        $response->assertStatus(302);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'done',
        ]);
    }

    public function test_non_assignee_cannot_update_status(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $other = User::factory()->create();

        $project = Project::create([
            'title' => 'Owner Project',
            'description' => null,
            'deadline' => date('Y-m-d'),
            'user_id' => $owner->id,
        ]);

        $task = Task::create([
            'title' => 'Assigned Task',
            'status' => 'in progress',
            'due_date' => null,
            'project_id' => $project->id,
            'assigned_to' => $assignee->id,
        ]);

        $response = $this
            ->actingAs($other)
            ->put(route('tasks.update', $task->id), [
                'status' => 'done',
            ]);

        $response->assertStatus(403);

        // status should remain unchanged
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in progress',
        ]);
    }
}
