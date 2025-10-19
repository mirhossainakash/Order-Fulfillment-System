<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'seller']),
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 5, 200),
            'stock_quantity' => fake()->numberBetween(5, 100),
        ];
    }
}
