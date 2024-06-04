<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    protected array $urls;

    public function __construct(Account $model)
    {
        parent::__construct($model);

        $this->urls = array(
            'index' => url('awesome_admin/user/'),
            'show' => url('awesome_admin/user/profile/'),
            'create' => url('awesome_admin/user/create'),
            'store' => url('awesome_admin/user/store'),
            'edit' => url('awesome_admin/user/edit/'),
            'update' => url('awesome_admin/user/update/'),
            'destroy' => url('awesome_admin/user/destroy/')
        );
    }

    public function create(array $attr): Account
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

    public function update(array $attr, $idOrSlug): Account
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

        return parent::update($attr, $idOrSlug);
    }

    public function getUrls(): array
    {
        return $this->urls;
    }

    public function setUrls(array $urls)
    {
        $this->urls = $urls;
        
        return $this;
    }
}
