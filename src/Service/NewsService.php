<?php

namespace App\Service;
use App\Model\News;
use App\Repository\NewsRepositoryInterface;

class NewsService implements NewsServiceInterface
{
    public function __construct(
        private NewsRepositoryInterface $repo
    ) {}

    /** @return News[] */
    public function listAllNews(): array
    {
        return $this->repo->findAll();
    }

    public function createNews(array $input): int
    {
        $news = News::createNew(
            $input['title'],
            $input['description']
        );
        return $this->repo->create($news);
    }

    public function updateNews(int $id, array $input): void
    {
        $existing = $this->repo->findById($id);
        if (!$existing) {
            throw new \RuntimeException('News not found');
        }

        $existing->setTitle($input['title']);
        $existing->setDescription($input['description']);

        $this->repo->update($existing);
    }

    public function deleteNews(int $id): void
    {
        $this->repo->delete($id);
    }
}
