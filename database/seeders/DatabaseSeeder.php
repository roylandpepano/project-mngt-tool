<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin and a normal user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'role' => 'user',
        ]);

        // Create projects for both users
        $projectsForAdmin = Project::factory()->count(3)->create(['user_id' => $admin->id]);
        $projectsForUser = Project::factory()->count(3)->create(['user_id' => $user->id]);

        // Create tasks for projects; some assigned to users
        foreach (Project::all() as $project) {
            for ($i = 0; $i < 3; $i++) {
                $assigned = null;
                if (rand(0, 1) === 1) {
                    $assigned = (rand(0,1) === 1) ? $admin->id : $user->id;
                }

                Task::factory()->create([
                    'project_id' => $project->id,
                    'assigned_to' => $assigned,
                ]);
            }
        }
    }
}
