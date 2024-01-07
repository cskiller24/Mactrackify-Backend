<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WarehouseItem>
 */
class WarehouseItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warehouse_id' => 1,
            'name' => fake()->words(mt_rand(3, 10), true),
            'quantity' => mt_rand(1, 100),
            'price' => mt_rand(1, 1000),
            'description' => fake()->sentence(mt_rand(1, 3)),
        ];
    }
}
