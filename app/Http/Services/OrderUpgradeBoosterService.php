<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\OrderUpgradeBooster;
use App\Models\Product;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Storage;

class OrderUpgradeBoosterService
{
    public function create(array $attr): ?OrderUpgradeBooster
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            $data = new OrderUpgradeBooster($attr);
            if ($data->save()) {
                DB::commit();

                return $data;
            }

            // Rollback the transaction if saving fails
            DB::rollBack();
            throw new Exception("Failed to save new booster add-on to database");
        } catch (Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();
            throw $e;
        }
    }

    public function update(OrderUpgradeBooster $orderUpgradeBooster, array $attr): ?OrderUpgradeBooster
    {
        if (isset($attr['slug'])) {
            $attr['slug'] = Helper::generateUniqueSlug($attr['slug'], OrderUpgradeBooster::class, $orderUpgradeBooster->id);
        }

        $updatedData = $orderUpgradeBooster->fill($attr);

        if ($updatedData->save()) {
            return $updatedData;
        }

        throw new \Exception("Failed to store updated data");
    }

    public function findById($idOrSlug): ?OrderUpgradeBooster
    {
        $data = OrderUpgradeBooster::find($idOrSlug);

        if (!$data) {
            $data = OrderUpgradeBooster::where('slug', $idOrSlug)->first();
        }

        return $data;
    }

    public function findByIndexes(array $indexes, bool $any = false): Collection
    {
        $table = (new OrderUpgradeBooster)->getTable();
        $columns = Schema::getColumnListing($table);
        $query = OrderUpgradeBooster::query();

        foreach ($indexes as $column => $value) {
            if (in_array($column, $columns)) {
                $any
                    ? $query->where($column, $value)
                    : $query->orWhere($column, $value);
            }
        }

        return $query->get();
    }

    /**
     * List all user.
     */
    public function list(): Collection
    {
        return OrderUpgradeBooster::all();
    }

    public function delete(OrderUpgradeBooster $booster): ?bool
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            if (!$booster->delete()) {
                DB::rollBack();
                throw new Exception("Failed to save new booster add-on to database");
            }
           
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
