<?php

namespace App\Services;

class UserService extends BaseService
{
    public function __construct(\App\Models\Account $model)
    {
        parent::__construct($model);
    }
}
