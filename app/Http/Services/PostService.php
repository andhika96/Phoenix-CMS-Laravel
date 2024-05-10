<?php

namespace App\Services;

use App\Contracts\IPostService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Helper\Helper;
use App\Models\Post;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService extends BaseService
{
    public function __construct(\App\Models\User $model)
    {
        parent::__construct($model);
    }
}
