<?php

namespace App\Services;

use App\Contracts\IProductBodyService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductBody;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProductBodyService implements IProductBodyService
{
    public function create(array $attr): ?ProductBody
    {
        $data = new ProductBody($attr);
        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductBody $productBody, array $attr): ?ProductBody
    {
        $updatedData = $productBody->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($id): ?ProductBody
    {
        return ProductBody::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductBody())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductBody::query();
        
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
        return ProductBody::all();
    }

    public function delete(ProductBody $productBody): ?bool
    {
        return $productBody->delete();
    }
}
