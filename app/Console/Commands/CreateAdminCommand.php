<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     */
    protected $description = 'Create Admin Account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hasErrors = false;
        foreach (User::rolesList() as $role) {
            if(! Role::query()->whereName($role)->exists()) {
                $this->error("Role {$role} does not exists");
                $hasErrors = true;
            }
        }

        if($hasErrors) {
            $this->info('Please run the command "php artisan app:roles-create" to create roles');
            return Command::FAILURE;
        }

        $hasAdmin = User::query()->whereHas('roles', function ($q) {
            $q->whereName(User::ADMIN);
        })->exists();

        if($hasAdmin) {
            if(! $this->confirm('There\'s already an admin account, do you want to continue?')) {
                return Command::FAILURE;
            }
        }

        $first_name = $this->ask("Enter your first name");
        $middle_name = $this->ask("Enter your middle name (optional):", "");
        $last_name = $this->ask("Enter your last name");
        $email = $this->ask("Enter your email");
        $password = $this->secret("Enter your password");
        $password_confirmation = $this->secret("Confirm your password");

        return $this->createAdmin(compact(
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'password',
            'password_confirmation'
        ));
    }

    public function createAdmin($credentials)
    {
        $validator = Validator::make($credentials, [
            'first_name' => ['required'],
            'middle_name' => ['nullable'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed']
        ]);

        if($validator->fails()) {
            $this->error("Cannot create admin account (see errors below).");

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return Command::FAILURE;
        }

        User::query()->create([
            'first_name' => $credentials['first_name'],
            'middle_name' => $credentials['middle_name'],
            'last_name' => $credentials['last_name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ])->assignRole(User::ADMIN);

        $this->info("Admin added successfully");

        return Command::SUCCESS;
    }
}
