<?php

namespace App\Services;

use App\Contracts\IProductCylinderService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductCylinder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProductCylinderService implements IProductCylinderService
{
    public function create(array $attr): ?ProductCylinder
    {
        $data = new ProductCylinder($attr);
        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductCylinder $productCylinder, array $attr): ?ProductCylinder
    {
        $updatedData = $productCylinder->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($id): ?ProductCylinder
    {
        return ProductCylinder::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductCylinder())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductCylinder::query();
        
        foreach ($indexes as $column => $value) {
            if ($comparator == QueryAcceptedComparatorEnum::LIKE) {
                $value = "%{$value}%";
            }

            if (in_array($column, $columns)) {
                $any
                    ? $query->orWhere($column, $comparator->value, $value)
                    : $query->where($column, $comparator->value, $value);
            }
        }

        if ($limit == -1) {
            $results = $query->get();
        } else {
            $perPage = $limit;
            $currentPage = request()->get('page', 1);
            $results = $query->paginate($perPage, ['*'], 'page', $currentPage);
        }

        return $results;
    }

    public function list(): Collection
    {
        return ProductCylinder::all();
    }

    public function delete(ProductCylinder $productCylinder): ?bool
    {
        return $productCylinder->delete();
    }
}
