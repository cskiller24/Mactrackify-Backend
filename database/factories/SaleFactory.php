<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $team = Team::first();
        $members = $team->members()->inRandomOrder()->get()->pluck('id')->toArray();
        return [
            'team_id' => $team->id,
            'team_leader_id' => $team->leaders->first()->id,
            'brand_ambassador_id' => Arr::random($members),
            'customer_name' => fake()->name(),
            'customer_contact' => fake()->randomElement([fake()->email(), fake()->mobileNumber()]),
            'customer_age' => rand(18, 60),
            'product' => fake()->words(3, true),
            'product_quantity' => mt_rand(1, 10),
            'promo' => fake()->words(3, true),
            'promo_quantity' => mt_rand(1, 10),
            'signature' => 'test_signature.png',
        ];
    }
}
