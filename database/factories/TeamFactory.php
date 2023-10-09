<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'location' => fake()->city()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Team $team) {
            $teamLeader = User::teamLeader()
                ->withoutTeam()
                ->first();

            $brandAmbassadors = User::brandAmbassador()
                ->withoutTeam()
                ->get()
                ->pluck('id');
            $team->users()->attach($teamLeader->id, ['is_leader' => true]);
            $team->users()->attach($brandAmbassadors);
        });
    }
}
