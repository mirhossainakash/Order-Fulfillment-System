<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create buyers and sellers
        $buyers = User::factory()->count(3)->state(['role' => 'buyer'])->create();
        $sellers = User::factory()->count(2)->state(['role' => 'seller'])->create();

        foreach ($sellers as $seller) {
            Product::factory()->count(5)->create(['user_id' => $seller->id]);
        }

        // Default test user
        User::factory()->create([
            'name' => 'Buyer User',
            'email' => 'buyer@example.com',
            'role' => 'buyer',
            'password' => bcrypt('password'),
        ]);
        User::factory()->create([
            'name' => 'Seller User',
            'email' => 'seller@example.com',
            'role' => 'seller',
            'password' => bcrypt('password'),
        ]);
    }
}
