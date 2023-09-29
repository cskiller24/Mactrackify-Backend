<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->createUser(User::ADMIN, 1, ['email' => 'admin@macprod.com', 'password' => bcrypt('admin')]);
        $this->createUser(User::TEAM_LEADER, 1, ['email' => 'teamleader@macprod.com', 'password' => bcrypt('admin')]);
        $this->createUser(User::BRAND_AMBASSADOR, 1, ['email' => 'brandambassador@macprod.com', 'password' => bcrypt('admin')]);
        $this->createUser(User::HUMAN_RESOURCE, 1, ['email' => 'humanresource@macprod.com', 'password' => bcrypt('admin')]);
        // $this->createUser(User::TEAM_LEADER, 5);
        $this->createUser(User::HUMAN_RESOURCE, 5);
        $this->createUser(User::BRAND_AMBASSADOR, 20);
    }

    protected function createUser(string|array $role, int $count = 1, array $attributes = [])
    {
        $users = User::factory()
            ->count($count)
            ->create($attributes);

        $users->each(function (User $user) use ($role) {
            $user->assignRole($role);
        });
    }
}
