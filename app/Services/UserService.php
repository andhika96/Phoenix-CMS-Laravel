<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    public function __construct(\App\Models\Account $model)
    {
        parent::__construct($model);
    }

    public function create(array $attr)
    {
        $attr['uuid'] ??= Str::uuid();
        $attr['username'] ??= $attr['email'];
        $attr['remember_token'] ??= "";
        $attr['roles'] ??= 1;
        $attr['role_code'] ??= "role_code";
        $attr['recovery_code'] ??= uniqid("laraphoexRecovery_");
        $attr['recovery_code_duration'] ??= now()->addYears(3)->diffInSeconds(now());
        $attr['token'] ??= uniqid("laraphoexPAT_");
        $attr['password'] ??= "LaraPhoex2024";

        return parent::create($attr);
    }
}
