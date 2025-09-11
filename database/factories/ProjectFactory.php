<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dt = $this->faker->optional()->dateTimeBetween('now', '+2 years');
        $deadline = $dt ? $dt->format('Y-m-d') : date('Y-m-d');

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'deadline' => $deadline,
        ];
    }
}
