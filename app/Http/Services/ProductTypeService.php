<?php

namespace App\Services;

use App\Contracts\IProductTypeService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProductTypeService implements IProductTypeService
{
    public function create(array $attr): ?ProductType
    {
        $data = new ProductType($attr);
        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductType $productColor, array $attr): ?ProductType
    {
        $updatedData = $productColor->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($id): ?ProductType
    {
        return ProductType::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductType())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductType::query();
        
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
        return ProductType::all();
    }

    public function delete(ProductType $productColor): ?bool
    {
        return $productColor->delete();
    }
}
