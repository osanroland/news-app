<?php

namespace App\Service;

use App\Model\News;

interface NewsServiceInterface
{
    /** @return News[] */
    public function listAllNews(): array;

    public function createNews(array $input): int;

    public function updateNews(int $id, array $input): void;

    public function deleteNews(int $id): void;
}
