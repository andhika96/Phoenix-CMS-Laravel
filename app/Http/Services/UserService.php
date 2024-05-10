<?php

namespace App\Services;

use App\Contracts\IUserService;
use App\Models\User;

class UserService extends BaseService implements IUserService 
{
    public function __construct(\App\Models\User $model)
    {
        parent::__construct($model);
    }
}
