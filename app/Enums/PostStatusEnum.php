<?php

namespace App\Enums;

enum PostStatusEnum: string
{
    case PUBLISHED = 'PUBLISHED';
    case DRAFT = 'DRAFT';
    case INTERNAL = 'INTERNAL';
}
