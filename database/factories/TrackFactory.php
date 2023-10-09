<?php

namespace Database\Factories;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Track>
 */
class TrackFactory extends Factory
{
    public function definition(): array
    {
        $brandAmbassador = User::brandAmbassador()
            ->withTeam()
            ->inRandomOrder()
            ->first();

        throw_if(! $brandAmbassador, "Cannot create track when brand ambassador does not exists");

        return [
            'brand_ambassador_id' => $brandAmbassador->id,
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'location' => fake()->streetAddress(),
            'is_authentic' => fake()->boolean(90),
        ];
    }
}
