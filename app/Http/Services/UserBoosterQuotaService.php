<?php

namespace App\Services;

use App\Models\UserBoosterQuota;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserBoosterQuotaService
{
    public function create(array $attr): ?UserBoosterQuota
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            $data = new UserBoosterQuota($attr);
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


    public function findById($id): ?UserBoosterQuota
    {
        return UserBoosterQuota::find($id);
    }

    public function findByIndexes(array $indexes, bool $any = false): Collection
    {
        $table = (new UserBoosterQuota)->getTable();
        $columns = Schema::getColumnListing($table);
        $query = UserBoosterQuota::query();

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
        return UserBoosterQuota::all();
    }

    public function delete(UserBoosterQuota $order): ?bool
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
