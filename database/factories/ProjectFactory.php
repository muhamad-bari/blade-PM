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
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'is_pinned' => fake()->boolean(20),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'deadline' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed']),
            'leader_employee_id' => \App\Models\Employee::factory(),
        ];
    }
}
