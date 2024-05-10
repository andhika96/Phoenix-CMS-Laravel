<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\OrderUpgradePackage;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderUpgradePackageService
{
    public function create(array $attr): ?OrderUpgradePackage
    {
        // Begin a database transaction
        DB::beginTransaction();
        
        try {
            $data = new OrderUpgradePackage($attr);
            if ($data->save()) {
                DB::commit();

                return $data;
            }

            // Rollback the transaction if saving fails
            DB::rollBack();
            throw new Exception("Failed to save new order add-on to database");
        } catch (Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();
            throw $e;
        }
    }

    public function update(OrderUpgradePackage $OrderUpgradePackage, array $attr): ?OrderUpgradePackage
    {
        if (isset($attr['slug'])) {
            $attr['slug'] = Helper::generateUniqueSlug($attr['slug'], OrderUpgradePackage::class, $OrderUpgradePackage->id);
        }

        $updatedData = $OrderUpgradePackage->fill($attr);

        if ($updatedData->save()) {
            return $updatedData;
        }

        throw new \Exception("Failed to store updated data");
    }

    public function findById($idOrSlug): ?OrderUpgradePackage
    {
        $data = OrderUpgradePackage::find($idOrSlug);

        if (!$data) {
            $data = OrderUpgradePackage::where('slug', $idOrSlug)->first();
        }

        return $data;
    }

    public function findByIndexes(array $indexes, bool $any = false): Collection
    {
        $table = (new OrderUpgradePackage)->getTable();
        $columns = Schema::getColumnListing($table);
        $query = OrderUpgradePackage::query();

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
        return OrderUpgradePackage::all();
    }

    public function delete(OrderUpgradePackage $order): ?bool
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            if (!$order->delete()) {
                DB::rollBack();
                throw new Exception("Failed to save new order add-on to database");
            }
           
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
