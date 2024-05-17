<?php

namespace App\Contracts;

use App\Enums\QueryAcceptedComparatorEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface for base service operations.
 */
interface IBaseService
{
    /**
     * Create a new record.
     *
     * @param array $attr Data to create the record.
     * @return Model Created model instance.
     */
    public function create(array $attr);

    /**
     * Create multiple records.
     *
     * @param array $data Array of data to create multiple records.
     * @return array of created records.
     */
    public function createMany(array $data): array;

    /**
     * Find a record by its ID.
     *
     * @param mixed $id ID of the record to find.
     * @return Model|null Model instance if found, otherwise null.
     */
    public function findById($id): ?Model;

    /**
     * Find records by multiple indexes.
     *
     * @param array $indexes Array of indexes to search.
     * @param bool $any Whether to search for records matching any index.
     * @param int $limit Maximum number of records to return.
     * @param array $orderBy Array of columns to order the results by.
     * @param QueryAcceptedComparatorEnum $comparator Comparator for the query.
     * @return list of data matching the criteria.
     */
    public function findByIndexes(array $indexes, bool $any, ?int $limit, array $orderBy, QueryAcceptedComparatorEnum $comparator);

    /**
     * Get all records
     *
     * @return Collection Model instance.
     */
    public function list(): Collection;

    /**
     * Update a records
     * 
     * @param array attributes data.
     * @param $idOrSlug id or slug of instance to delete.
     * @return Model updated instance
     */
    public function update(array $data, $idOrSlug): ?Model;

    /**
     * Delete a record.
     *
     * @param $idOrSlug id or slug of instance to delete.
     * @return void
     */
    public function delete($idOrSlug): void;

    /**
     * Delete multiple records.
     *
     * @param array $data Array of model instances to delete.
     * @return ?int total successfully deleted data
     */
    public function deleteMany(array $data): ?int;
}
