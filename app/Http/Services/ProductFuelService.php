<?php

namespace App\Services;

use App\Contracts\IProductFuelService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductFuel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProductFuelService implements IProductFuelService
{
    public function create(array $attr): ?ProductFuel
    {
        $data = new ProductFuel($attr);
        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductFuel $productFuel, array $attr): ?ProductFuel
    {
        $updatedData = $productFuel->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($id): ?ProductFuel
    {
        return ProductFuel::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductFuel())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductFuel::query();
        
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
        return ProductFuel::all();
    }

    public function delete(ProductFuel $productFuel): ?bool
    {
        return $productFuel->delete();
    }
}
