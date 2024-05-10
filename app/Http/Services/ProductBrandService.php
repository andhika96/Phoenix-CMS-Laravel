<?php

namespace App\Services;

use App\Contracts\IProductBrandService;
use App\Enums\BrandTypeEnum;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductBrand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductBrandService implements IProductBrandService
{
    public function create(array $attr): ?ProductBrand
    {
        $image = $attr['brandImg'] ?? null;
        $icon = $attr['brandIcon'] ?? null;
        $attr['slug'] = $attr['slug'] ?? Str::slug($attr['name']);
        $attr['slug'] = $this->generateUniqueSlug($attr['slug']);

        $data = new ProductBrand($attr);
        $data->image = $this->uploadImage($image, "brand_image");
        $data->icon = $this->uploadImage($icon, "brand_icon");

        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductBrand $productBrand, array $attr): ?ProductBrand
    {
        $image = $attr['brandImg'] ?? null;
        $icon = $attr['brandIcon'] ?? null;

        if (isset($attr['slug'])) {
            $attr['slug'] = $this->generateUniqueSlug($attr['slug'], $productBrand->id);
        }

        if ($image) {
            $this->deleteImage($productBrand->image, "brand_image");
            $attr['image'] = $this->uploadImage($image, "brand_image");
        }

        if ($icon) {
            $this->deleteImage($productBrand->icon, "brand_icon");
            $attr['icon'] = $this->uploadImage($icon, "brand_icon");
        }

        $updatedData = $productBrand->fill($attr);

        if ($updatedData->save()) {
            return $updatedData;
        }

        throw new \Exception("Failed to store updated data brand");
    }

    public function findById($id): ?ProductBrand
    {
        return ProductBrand::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductBrand())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductBrand::query();
        
        foreach ($indexes as $column => $value) {
            if ($comparator == QueryAcceptedComparatorEnum::LIKE) {
                $value = "%{$value}%";
            }

            if (in_array($column, $columns)) {
                $any
                    ? $query->orWhere($column, $comparator->value, $value)
                    : $query->where($column, $comparator->value, $value);
            }

            if ($column == "brand_type") {
                $query->orWhere('brand_type', '=', BrandTypeEnum::SEMUA);
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
        return ProductBrand::all();
    }

    public function delete(ProductBrand $productBrand): ?bool
    {
        return $productBrand->delete();
    }

    protected function generateUniqueSlug(string $slug, $currentModelId = null): string
    {
        $query = ProductBrand::where('slug', $slug);

        // Exclude the current model's ID from the uniqueness check
        if ($currentModelId !== null) {
            $query->where('id', '!=', $currentModelId);
        }

        $count = $query->count();

        while ($count > 0) {
            $slug = $slug . '-' . ($count + 1);

            // Re-run the query to check the new slug's uniqueness
            $count = ProductBrand::where('slug', $slug)
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
