<?php

namespace App\Contracts;

use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductType;

interface IProductTypeService
{
    public function create(array $data);
    public function update(ProductType $productBrandModel, array $data);
    public function findById($id);
    public function findByIndexes(array $indexes, bool $any, int $limit, QueryAcceptedComparatorEnum $comparator);
    public function list();
    public function delete(ProductType $productBrandModel);
}
