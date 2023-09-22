<?php

namespace App\Rules\Abstract;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

abstract class Role implements ValidationRule
{
    private string $role;

    private function getRole()
    {
        return $this->role;
    }

    protected function setRole(string $role)
    {
        if(! in_array($role, User::rolesList())) {
            throw new \RuntimeException("Role {$role} does not match to the list of roles");
        }

        $this->role = $role;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(! $this->getRole()) {
            throw new \RuntimeException("Please set a role");
        }

        $user = User::query()->find($value);

        if(! $user) {
            $fail("The :attribute does not detect any user with id of :value");
        }

        if(! $user->hasRole($this->getRole())) {
            $fail("The :attribute does not match the role {$this->getRole()}");
        }
    }
}
