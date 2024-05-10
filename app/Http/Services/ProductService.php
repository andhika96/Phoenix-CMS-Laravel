<?php

namespace App\Services;

use App\Contracts\IProductService;
use App\Enums\PostStatusEnum;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Helper\Helper;
use App\Models\Product;
use App\Models\User;
use App\Models\UserBoosterQuota;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService implements IProductService
{
    public function create(array $attr): ?Product
    {
        // Log::debug(json_encode($attr));
        $frontImg = $attr['front_img'] ?? null;
        $backImg = $attr['back_img'] ?? null;
        $leftImg = $attr['left_img'] ?? null;
        $rightImg = $attr['right_img'] ?? null;
        $kmImg = $attr['km_img'] ?? null;
        $attr['slug'] = $attr['slug'] ?? Str::slug($attr['name']);
        $attr['slug'] = Helper::generateUniqueSlug($attr['slug'], Product::class);

        $data = new Product($attr);
        $data->front_img = $frontImg?->path;
        $data->back_img = $backImg?->path;
        $data->left_img = $leftImg?->path;
        $data->right_img = $rightImg?->path;
        $data->km_img = $kmImg?->path;

        if (!$data->save()) {
            throw new \Exception("Failed store product to database");
        }

        return $data;
    }

    public function update(Product $product, array $attr): ?Product
    {
        $attr['front_img'] = $attr['front_img'] ?? null;
        $attr['back_img'] = $attr['back_img'] ?? null;
        $attr['left_img'] = $attr['left_img'] ?? null;
        $attr['right_img'] = $attr['right_img'] ?? null;
        $attr['km_img'] = $attr['km_img'] ?? null;

        if (isset($attr['slug'])) {
            $attr['slug'] = $this->generateUniqueSlug($attr['slug'], $product->id);
        } 

        if (filter_var($attr['front_img'], FILTER_VALIDATE_URL)) {
            unset($attr['front_img']);
        }
        
        if (filter_var($attr['back_img'], FILTER_VALIDATE_URL)) {
            unset($attr['back_img']);
        }
        
        if (filter_var($attr['left_img'], FILTER_VALIDATE_URL)) {
            unset($attr['left_img']);
        }
        
        if (filter_var($attr['right_img'], FILTER_VALIDATE_URL)) {
            unset($attr['right_img']);
        }
        
        if (filter_var($attr['km_img'], FILTER_VALIDATE_URL)) {
            unset($attr['km_img']);
        }

        Log::debug(json_encode($attr));

        $updatedData = $product->fill($attr);
        if ($updatedData->save()) {
            return $updatedData;
        }
    }

    public function findById($idOrSlug): ?Product
    {
        $product = Product::find($idOrSlug);

        if (!$product) {
            $product = Product::where('slug', $idOrSlug)->first();
        }

        if ($product && $product->status == PostStatusEnum::PUBLISHED->value) {
            $filter = implode(",", array($product->brand_id, $product->model_id, $product->variant_id));
            views($product)
                ->useVisitor(app(Visitor::class))
                ->collection($filter)
                ->record();
        }

        return $product;
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        $limit,
        array $orderBy,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        $table = (new Product())->getTable();
        $columns = Schema::getColumnListing($table);
        $query = Product::query();
        // Log::debug($indexes);
        foreach ($indexes as $column => $value) {
            if ($comparator == QueryAcceptedComparatorEnum::LIKE) {
                $value = "%{$value}%";
            }

            if (in_array($column, $columns)) {
                $query->when($any, function ($query) use ($column, $comparator, $value) {
                    $query->orWhere($column, $comparator->value, $value);
                }, function ($query) use ($column, $comparator, $value) {
                    $query->where($column, $comparator->value, $value);
                });
            }
        }
        $query->when(isset($indexes['favoritedByUser']), function ($query) use ($indexes) {
            $query->whereHas('favoritedByUsers', function ($queryFav) use ($indexes) {
                $queryFav->where('user_id', $indexes['favoritedByUser']);
            });
        })->when(!empty($indexes['boosterType']) && $indexes['boosterType'] == 'basic', function ($query) {
            // Non-ads query
            $query->doesnthave('booster');
            // Log::debug("aye");
        })->when(!empty($indexes['booster_type_id']), function ($query) use ($indexes) {
            // Ads query
            $query->whereHas('activeBooster', function ($queryBoost) use ($indexes) {
                $queryBoost->where('booster_id', $indexes['booster_type_id']);
            });
        })->when(isset($indexes['start_year']) && isset($indexes['end_year']), function ($query) use ($indexes) {
            $query->whereBetween('release_year', [$indexes['start_year'], $indexes['end_year']]);
        })->when(isset($indexes['start_price']) && isset($indexes['end_price']), function ($query) use ($indexes) {
            $startPrice = str_replace('.', '', $indexes['start_price']);
            $endPrice = str_replace('.', '', $indexes['end_price']);
            $query->whereBetween('price', [(int)$startPrice, (int)$endPrice]);
        })->when(isset($indexes['start_kilometer']) && isset($indexes['end_kilometer']), function ($query) use ($indexes) {
            $query->whereBetween('kilometer', [$indexes['start_kilometer'], $indexes['end_kilometer']]);
        })->when(isset($indexes['ignore']), function ($query) use ($indexes) {
            $query->whereNotIn('id', $indexes['ignore']);
        })->when(!empty($indexes['special_sort']) && $indexes['special_sort'] === 'random', function ($query) {
            $query->inRandomOrder();
        });

        if (!empty($orderBy) && empty($indexes['special_sort'])) {
            foreach ($orderBy as $orderByColumn) {
                $orderByArray = explode(' ', $orderByColumn);
                $orderByColumn = $orderByArray[0];
                $orderByDirection = isset($orderByArray[1]) ? $orderByArray[1] : 'ASC';

                if (in_array($orderByColumn, $columns)) {
                    $query->orderBy($orderByColumn, $orderByDirection);
                }
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

    public function toggleFavorite(User $user, Product $product): bool
    {
        if ($user->favorites()->where('product_id', $product->id)->exists()) {
            $user->favorites()->detach($product->id);
            return false; // Product removed from favorites
        } else {
            $user->favorites()->attach($product->id);
            return true; // Product added to favorites
        }
    }

    /**
     * List all user.
     */
    public function list(): Collection
    {
        return Product::all();
    }

    public function total(): int
    {
        return Product::count();
    }

    public function patchIsActive(Product $product, bool $isActive): Product
    {
        $product->is_active = $isActive;
        $product->save();

        return $product;
    }

    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }

    public function boostProduct(Product $product, UserBoosterQuota $booster)
    {
        DB::beginTransaction();
        try {
            $booster->product_id = $product->id;
            $booster->start_at = Carbon::now();
            $booster->expired_at = Carbon::now()->addDays($booster->boosterType->duration ?? 0);
            // $booster->expired_at = Carbon::now()->addMinutes(5);
            if (!$booster->save()) {
                throw new \Exception("unexpected error when save booster to database");
            }
            DB::commit();
            $product->refresh();

            return $product;
        } catch (\Throwable $th) {
            // Rollback the transaction in case of an exception
            DB::rollBack();
            throw $th;
        }
    }

    protected function generateUniqueSlug(string $slug, $currentModelId = null): string
    {
        $query = Product::where('slug', $slug);

        // Exclude the current model's ID from the uniqueness check
        if ($currentModelId !== null) {
            $query->where('id', '!=', $currentModelId);
        }

        $count = $query->count();

        while ($count > 0) {
            $slug = $slug . '-' . ($count + 1);

            // Re-run the query to check the new slug's uniqueness
            $count = Product::where('slug', $slug)
                ->where('id', '!=', $currentModelId) // Exclude current model's ID
                ->count();
        }

        return $slug;
    }

    protected function uploadImage($image, $type = "product")
    {
        if ($image) {
            $fileName = uniqid("{$type}_") . '.' . $image->getClientOriginalExtension();
            $image->storeAs("{$type}", $fileName, 'public');

            return $fileName;
        }

        return null;
    }

    protected function deleteImage($image, $type = "product")
    {
        if ($image) {
            Storage::disk('public')->delete("{$type}/{$image}");
        }
    }
}
