<?php

namespace App\Services;

use App\Contracts\IProductBrandModelService;
use App\Contracts\IProductBrandService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductBrandModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductBrandModelService implements IProductBrandModelService
{
    public function create(array $attr): ?ProductBrandModel
    {
        $image = $attr['modelImg'] ?? null;
        $icon = $attr['modelIcon'] ?? null;
        $attr['slug'] = $attr['slug'] ?? Str::slug($attr['name']);
        $attr['slug'] = $this->generateUniqueSlug($attr['slug']);

        $data = new ProductBrandModel($attr);
        $data->image = $this->uploadImage($image, "model_image");
        $data->icon = $this->uploadImage($icon, "model_icon");

        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductBrandModel $productBrandModel, array $attr): ?ProductBrandModel
    {
        $image = $attr['modelImg'] ?? null;
        $icon = $attr['modelIcon'] ?? null;

        if (isset($attr['slug'])) {
            $attr['slug'] = $this->generateUniqueSlug($attr['slug'], $productBrandModel->id);
        }

        if ($image) {
            $this->deleteImage($productBrandModel->image, "model_image");
            $attr['image'] = $this->uploadImage($image, "model_image");
        }

        if ($icon) {
            $this->deleteImage($productBrandModel->icon, "model_icon");
            $attr['icon'] = $this->uploadImage($icon, "model_icon");
        }


        $updatedData = $productBrandModel->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($id): ?ProductBrandModel
    {
        return ProductBrandModel::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductBrandModel())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductBrandModel::with('brand');

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

    /**
     * List all user.
     */
    public function list(): Collection
    {
        return ProductBrandModel::all();
    }

    public function delete(ProductBrandModel $productBrandModel): ?bool
    {
        return $productBrandModel->delete();
    }

    protected function generateUniqueSlug(string $slug, $currentModelId = null): string
    {
        $query = ProductBrandModel::where('slug', $slug);

        // Exclude the current model's ID from the uniqueness check
        if ($currentModelId !== null) {
            $query->where('id', '!=', $currentModelId);
        }

        $count = $query->count();

        while ($count > 0) {
            $slug = $slug . '-' . ($count + 1);

            // Re-run the query to check the new slug's uniqueness
            $count = ProductBrandModel::where('slug', $slug)
                ->where('id', '!=', $currentModelId) // Exclude current model's ID
                ->count();
        }

        return $slug;
    }

    protected function uploadImage($image, $type = "brand_image")
    {
        if ($image) {
            $fileName = uniqid("{$type}_") . '.' . $image->getClientOriginalExtension();
            $image->storeAs("{$type}", $fileName, 'public');

            return $fileName;
        }

        return null;
    }

    protected function deleteImage($image, $type = "brand_image")
    {
        if ($image) {
            Storage::disk('public')->delete("{$type}/{$image}");
        }
    }
}
