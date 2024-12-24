<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true), // Generates a 3-word name
            'description' => $this->faker->sentence, // Generates a short sentence
            'price' => $this->faker->randomFloat(2, 1, 100), // Generates a price between 1 and 100
        ];
    }
}
