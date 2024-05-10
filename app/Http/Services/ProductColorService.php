<?php

namespace App\Services;

use App\Contracts\IProductColorService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductColor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProductColorService implements IProductColorService
{
    public function create(array $attr): ?ProductColor
    {
        $data = new ProductColor($attr);
        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductColor $productColor, array $attr): ?ProductColor
    {
        $updatedData = $productColor->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($id): ?ProductColor
    {
        return ProductColor::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductColor())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductColor::query();
        
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
        return ProductColor::all();
    }

    public function delete(ProductColor $productColor): ?bool
    {
        return $productColor->delete();
    }
}
