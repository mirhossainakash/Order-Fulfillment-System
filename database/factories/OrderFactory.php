<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Order> */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => uniqid('ORD-'),
            'buyer_id' => User::factory()->state(['role' => 'buyer']),
            'total_amount' => 0,
            'status' => 'pending',
        ];
    }
}
