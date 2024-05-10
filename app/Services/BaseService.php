<?php

namespace App\Services;

use App\Contracts\IBaseService;
use App\Enums\QueryAcceptedComparatorEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseService
 * 
 * A base service class implementing common CRUD operations.
 * 
 * @package App\Services
 */
class BaseService implements IBaseService
{
    /** @var Model The model associated with this service. */
    protected Model $model;

    /**
     * Constructor.
     *
     * @param Model $model The Eloquent model associated with this service.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    /**
     * Create a new model instance and persist it to the database.
     *
     * @param array $attr The attributes for the new model instance.
     * @return Model The created model instance.
     * @throws \Exception If unable to save the model.
     */
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

    /**
     * Create multiple model instances and persist them to the database.
     *
     * @param array $data An array of attributes for the new model instances.
     * @return array An array of created model instances.
     */
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

    /**
     * Find a model by its ID or slug.
     *
     * @param mixed $idOrSlug The ID or slug of the model to find.
     * @return Model|null The found model instance, or null if not found.
     */
    public function findById($idOrSlug): ?Model
    {
        $query = $this->model->newQuery();
        $data = $query->find($idOrSlug);

        if (!$data && $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'slug')) {
            $data = $query->where('slug', $idOrSlug)->first();
        }

        return $data;
    }

    /**
     * Find model instances based on specified indexes.
     *
     * @param array $indexes An array of column-value pairs for filtering.
     * @param bool $any Whether to match any or all of the provided indexes.
     * @param int|null $limit The maximum number of results to return.
     * @param array $orderBy An array of columns to order the results by.
     * @param QueryAcceptedComparatorEnum $comparator The comparison operator for each index.
     * @return Collection|\Illuminate\Pagination\LengthAwarePaginator The matching model instances.
     */
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

    /**
     * Retrieve all model instances.
     *
     * @return Collection All model instances.
     */
    public function list(): Collection
    {
        return $this->model->all();
    }

    /**
     * Update a model instance.
     *
     * @param array $updateData The attributes to update.
     * @param mixed $idOrSlug The ID or slug of the model to update.
     * @return Model|null The updated model instance, or null if not found.
     * @throws \Exception If unable to update the model.
     */
    public function update(array $updateData, $idOrSlug): ?Model
    {
        DB::beginTransaction();

        try {
            $query = $this->model->newQuery();
            $modelName = get_class($this->model);

            // Find the model by ID or slug
            $model = $query->find($idOrSlug);

            if (!$model && $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'slug')) {
                $model = $query->where('slug', $idOrSlug)->first();
            }

            if (!$model) {
                throw new \Exception("Model not found");
            }

            // Update the model attributes with the provided data
            $model->fill($updateData);

            // Save the updated model
            if ($model->save()) {
                DB::commit();
                return $model;
            }

            // Rollback the transaction if saving fails
            DB::rollBack();
            throw new \Exception("Failed to update {$modelName}");
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a model instance by its ID or slug.
     *
     * @param mixed $idOrSlug The ID or slug of the model to delete.
     * @return void
     * @throws \Exception If unable to delete the model.
     */
    public function delete($idOrSlug): void
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            $query = $this->model->newQuery();
            $model = $query->find($idOrSlug);
            $modelName = get_class($this->model);

            if (!$model && $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'slug')) {
                $model = $query->where('slug', $idOrSlug)->first();
            }

            if (!$model) {
                throw new \Exception("Model not found");
            }

            // Delete the model
            if (!$model->delete()) {
                throw new \Exception("Failed to delete {$modelName}");
            }

            DB::commit();
        } catch (\Throwable $e) {
            // Rollback the transaction in case of an exception
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete multiple model instances by their IDs.
     *
     * @param array $ids The IDs of the model instances to delete.
     * @return int|null The number of deleted instances.
     * @throws \Exception If unable to delete the instances.
     */
    public function deleteMany(array $ids): ?int
    {
        $deletedCount = 0;

        DB::beginTransaction();

        try {
            $deletedCount = $this->model->newQuery()->whereIn('id', $ids)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete multiple records: {$e->getMessage()}");
            throw new \Exception("Failed to delete multiple records");
        }

        return $deletedCount;
    }
}
