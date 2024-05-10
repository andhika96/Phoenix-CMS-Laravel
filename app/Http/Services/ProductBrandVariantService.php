<?php

namespace App\Services;

use App\Contracts\IProductBrandVariantService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\ProductBrandVariant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductBrandVariantService implements IProductBrandVariantService
{
    public function create(array $attr): ?ProductBrandVariant
    {
        $image = $attr['varinatImg'] ?? null;
        $icon = $attr['variantIcon'] ?? null;
        $attr['slug'] = $attr['slug'] ?? Str::slug($attr['name']);
        $attr['slug'] = $this->generateUniqueSlug($attr['slug']);

        $data = new ProductBrandVariant($attr);
        $data->image = $this->uploadImage($image, "variant_image");
        $data->icon = $this->uploadImage($icon, "variant_icon");

        if ($data->save()) {
            return $data;
        }
    }

    public function update(ProductBrandVariant $productBrandVariant, array $attr): ?ProductBrandVariant
    {
        $image = $attr['variantImg'] ?? null;
        $icon = $attr['variantIcon'] ?? null;
        
        if (isset($attr['slug'])) {
            $attr['slug'] = $this->generateUniqueSlug($attr['slug'], $productBrandVariant->id);
        }
        
        if ($image) {
            $this->deleteImage($productBrandVariant->image, "variant_image");
            $attr['image'] = $this->uploadImage($image, "variant_image");
        }

        if ($icon) {
            $this->deleteImage($productBrandVariant->icon, "variant_icon");
            $attr['icon'] = $this->uploadImage($icon, "variant_icon");
        }


        $updatedData = $productBrandVariant->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($id): ?ProductBrandVariant
    {
        return ProductBrandVariant::find($id);
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new ProductBrandVariant())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = ProductBrandVariant::query();
        
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
        return ProductBrandVariant::all();
    }

    public function delete(ProductBrandVariant $productBrandVariant): ?bool
    {
        return $productBrandVariant->delete();
    }

    protected function generateUniqueSlug(string $slug, $currentModelId = null): string
    {
        $query = ProductBrandVariant::where('slug', $slug);

        // Exclude the current model's ID from the uniqueness check
        if ($currentModelId !== null) {
            $query->where('id', '!=', $currentModelId);
        }

        $count = $query->count();

        while ($count > 0) {
            $slug = $slug . '-' . ($count + 1);

            // Re-run the query to check the new slug's uniqueness
            $count = ProductBrandVariant::where('slug', $slug)
                ->where('id', '!=', $currentModelId) // Exclude current model's ID
                ->count();
        }

        return $slug;
    }

    protected function uploadImage($image, $type = "brand_variant")
    {
        if ($image) {
            $fileName = uniqid("{$type}_") . '.' . $image->getClientOriginalExtension();
            $image->storeAs("{$type}", $fileName, 'public');

            return $fileName;
        }

        return null;
    }

    protected function deleteImage($image, $type = "brand_variant")
    {
        if ($image) {
            Storage::disk('public')->delete("{$type}/{$image}");
        }
    }
}
