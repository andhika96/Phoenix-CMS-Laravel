<?php

namespace App\Contracts;

use App\Enums\QueryAcceptedComparatorEnum;
use App\Models\Post;

interface IPostService
{
    public function create(array $data);
    public function update(Post $post, array $data);
    public function findById($id);
    public function findByIndexes(array $indexes, bool $any, int $limit, array $orderBy, QueryAcceptedComparatorEnum $comparator);
    public function list();
    public function delete(Post $post);
}
