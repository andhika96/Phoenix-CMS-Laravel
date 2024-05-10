<?php

namespace App\Contracts;

use App\Enums\QueryAcceptedComparatorEnum;
use App\Enums\RegionModelEnum;

interface IRegionService
{
    public function find($keyword);
    public function findLocationByIndexes(
        array $indexes,
        bool $any,
        int $limit,
        RegionModelEnum $regionModel,
        QueryAcceptedComparatorEnum $comparator
    );
}
