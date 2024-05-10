<?php

namespace App\Services;

use App\Contracts\IProductCategoryService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ProductCategoryService implements IProductCategoryService
{
    public function create(array $attr): ?ProductCategory
    {
        $data = new ProductCategory($attr);
        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductCategory $productCategory, array $attr): ?ProductCategory
    {
        $updatedData = $productCategory->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($id): ?ProductCategory
    {
        return ProductCategory::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductCategory())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductCategory::query();
        
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
        return ProductCategory::all();
    }

    public function delete(ProductCategory $productCategory): ?bool
    {
        return $productCategory->delete();
    }
}
