<?php

namespace App\Contracts;

use App\Models\Media;

interface IMediaService
{
    public function create(array $attr);
    public function createMany(array $data);
    public function findById($id);
    public function findByIndexes(array $indexes, bool $any, int $limit);
    public function list();
    public function delete(Media $data);
    public function deleteMany(array $data);
}
