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
        foreach (User::rolesList() as $role) {
            try {
                Role::findOrCreate($role);
                $this->info("Created {$role} role");
            } catch (\Exception $e) {
                $this->error('Something went wrong');
                throw $e;
            }
        }

        $this->info('Roles created succesfully');

        return Command::SUCCESS;
    }
}
