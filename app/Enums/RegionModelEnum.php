<?php

namespace App\Enums;

enum RegionModelEnum: string
{
    case PROVINCE = 'province';
    case CITY = 'city';
    case DISTRICT = 'district';
    case VILLAGE = 'village';
}
