<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['todo', 'in progress', 'done'];

        $dt = $this->faker->optional()->dateTimeBetween('now', '+1 year');

        return [
            'title' => $this->faker->sentence(4),
            'status' => $this->faker->randomElement($statuses),
            'due_date' => $dt ? $dt->format('Y-m-d') : null,
            'project_id' => Project::factory(),
            'assigned_to' => null,
        ];
    }
}
