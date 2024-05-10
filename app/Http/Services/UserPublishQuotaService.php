<?php

namespace App\Services;

use App\Models\UserPublishQuota;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserPublishQuotaService
{
    public function create(array $attr): ?UserPublishQuota
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            $data = new UserPublishQuota($attr);
            if ($data->save()) {
                $data->user->assignRole('vip');

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


    public function findById($id): ?UserPublishQuota
    {
        return UserPublishQuota::find($id);
    }

    public function findByIndexes(array $indexes, bool $any = false): Collection
    {
        $table = (new UserPublishQuota)->getTable();
        $columns = Schema::getColumnListing($table);
        $query = UserPublishQuota::query();

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
        return UserPublishQuota::all();
    }

    public function delete(UserPublishQuota $order): ?bool
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
