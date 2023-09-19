<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class CreateRolesCommand extends Command
{
    protected $signature = 'app:roles-create';

    protected $description = 'Create roles';

    public function handle()
    {
        Role::findOrCreate(User::BRAND_AMBASSADOR);
        Role::findOrCreate(User::TEAM_LEADER);
        Role::findOrCreate(User::ADMIN);

        $this->info('Roles created succesfully');

        return Command::SUCCESS;
    }
}
