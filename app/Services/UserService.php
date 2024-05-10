<?php

namespace App\Services;

use App\Contracts\IUserService;

class UserService extends BaseService implements IUserService 
{
    public function __construct(\App\Models\Account $model)
    {
        parent::__construct($model);
    }
}
