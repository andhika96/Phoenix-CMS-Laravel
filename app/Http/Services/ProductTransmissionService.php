<?php

namespace App\Services;

use App\Contracts\IProductTransmissionService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductTransmission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProductTransmissionService implements IProductTransmissionService
{
    public function create(array $attr): ?ProductTransmission
    {
        $data = new ProductTransmission($attr);
        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductTransmission $productTransmission, array $attr): ?ProductTransmission
    {
        $updatedData = $productTransmission->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($id): ?ProductTransmission
    {
        return ProductTransmission::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductTransmission())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductTransmission::query();
        
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
        return ProductTransmission::all();
    }

    public function delete(ProductTransmission $productTransmission): ?bool
    {
        return $productTransmission->delete();
    }
}
