<?php

namespace Database\Factories\Workspace;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class WorkspacesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'name' => fake()->name(),
            // 'support_email' => fake()->unique()->safeEmail(),
            'name' => 'Axis LLC',
            'support_email' => 'support@axis.africa.com',
        ];
    }
}
