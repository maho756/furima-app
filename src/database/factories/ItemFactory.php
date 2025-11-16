<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'brand' => $this->faker->word(),
            'description' => $this->faker->sentence(10),
            'image_url' => 'https://via.placeholder.com/300',
            'condition' => $this->faker->randomElement(['良好', 'やや傷や汚れあり', '状態が悪い']),
            'sold_out' => false,
        ];
    }
}
