<?php

namespace App\Contracts;

use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\User;

interface IUserService
{
    public function addRole(User $user, $roles);
}
