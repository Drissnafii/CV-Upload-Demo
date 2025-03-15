<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CV>
 */
class CVFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'file_path' => 'cvs/fake_' . $this->faker->uuid() . '.pdf',
            'file_name' => 'resume_' . $this->faker->uuid() . '.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => $this->faker->numberBetween(100000, 5000000),
        ];
    }
}
