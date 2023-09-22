<?php

namespace App\Rules;

use App\Models\User;
use App\Rules\Abstract\Role;

class MustBeHumanResource extends Role
{
    public function __construct()
    {
        $this->setRole(User::HUMAN_RESOURCE);
    }
}

