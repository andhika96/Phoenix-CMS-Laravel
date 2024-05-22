<?php

namespace App\Services;

use App\Models\Awesome_Admin\Awesome_Admin;

class Awesome_AdminService extends BaseService
{
    public function __construct(Awesome_Admin $model)
    {
        parent::__construct($model);
    }

}
