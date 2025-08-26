<?php

namespace App\Repository;

use App\Model\News;

interface NewsRepositoryInterface
{
    /** @return News[] */
    public function findAll(): array;

    public function findById(int $id): ?News;

    public function create(News $news): int;

    public function update(News $news): bool;

    public function delete(int $id): bool;
}
