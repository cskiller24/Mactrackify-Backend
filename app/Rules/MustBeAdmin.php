<?php

namespace App\Rules;

use App\Models\User;
use App\Rules\Abstract\Role;

class MustBeAdmin extends Role
{
    public function __construct()
    {
        $this->setRole(User::ADMIN);
    }
}
