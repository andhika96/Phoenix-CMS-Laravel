<?php

namespace App\Services;

use App\Contracts\IBaseService;
use App\Enums\QueryAcceptedComparatorEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseService implements IBaseService
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    public function create(array $attr): Model
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            $modelName = get_class($this->model);

            $data = $this->model->newInstance($attr);

            // Save the new data to the database within the transaction
            if ($data->save()) {
                // Commit the transaction
                DB::commit();

                return $data;
            }

            // Rollback the transaction if saving fails
            DB::rollBack();
            throw new \Exception("Failed to save {$modelName} to database");
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();
            throw $e;
        }
    }

    public function createMany(array $data): array
    {
        $storedData = array();
        if (!empty($data)) {
            foreach ($data as $key => $item) {
                // Begin a database transaction
                DB::beginTransaction();
                try {
                    $data = $this->model->newInstance($item);
                    $data->save();
                    $storedData[] = $data->refresh();
                } catch (\Throwable $th) {
                    // Rollback the transaction in case of an exception
                    DB::rollBack();
                    Log::error($th->getMessage());
                }
            }
        }

        return $storedData;
    }

    public function findById($idOrSlug): ?Model
    {
        $query = $this->model->newQuery();
        $data = $query->find($idOrSlug);

        if (!$data && $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'slug')) {
            $data = $query->where('slug', $idOrSlug)->first();
        }

        return $data;
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        ?int $limit,
        array $orderBy,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL
    ) {
        // Get column names of the model's table
        $columns = $this->model->getConnection()->getSchemaBuilder()->getColumnListing($this->model->getTable());
        $query = $this->model->newQuery();
    
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
    
        // Ignore specific records if requested
        $query->when(isset($indexes['ignore']), function ($query) use ($indexes) {
            $query->whereNotIn('id', $indexes['ignore']);
        });
    
        // Apply special sorting (e.g., random)
        $query->when(!empty($indexes['special_sort']) && $indexes['special_sort'] === 'random', function ($query) {
            $query->inRandomOrder();
        });
    
        // Apply regular sorting if no special sorting is requested
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
    
        // Pagination configuration
        if ($limit === -1) {
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
        return $this->model->all();
    }

    public function delete($idOrSlug): void
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            $query = $this->model->newQuery();
            $data = $query->find($idOrSlug);
            $modelName = get_class($this->model);
    
            if (!$data && $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'slug')) {
                $data = $query->where('slug', $idOrSlug)->first();
            }
    
            if (!$data->delete()) {
                throw new \Exception("unexpected error when deleting {$modelName} to database");
            }

            DB::commit();
        } catch (\Throwable $e) {
           // Rollback the transaction in case of an exception
           DB::rollBack();
           throw $e;
        }
    }

    public function deleteMany(array $data): ?int
    {
        $deletedData = 0;

        DB::transaction(function () use ($data, &$deletedData) {
            $query = $this->model->newQuery();

            $mediaToDelete = $query->whereIn('id', $data)->get();
            $mediaToDelete->each(function ($item) use (&$deletedData) {
                if ($item->delete()) {
                    $deletedData++;
                }
            });
        });

        return $deletedData;
    }
}
