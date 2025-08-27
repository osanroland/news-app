<?php

namespace App\Repository;

use PDO;
use App\Model\News;
class NewsRepository implements NewsRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        $query = $this->pdo->prepare("
            SELECT id, title, description, created_at, updated_at
            FROM news
            ORDER BY id ASC
        ");
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return array_map(fn($r) => News::fromRow($r), $rows);
    }

    public function findById(int $id): ?News
    {
        $query = $this->pdo->prepare("
            SELECT id, title, description, created_at, updated_at
            FROM news
            WHERE id = :id
        ");
        $query->execute([':id' => $id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row ? News::fromRow($row) : null;
    }

    public function create(News $news): int
    {
        $sql = "INSERT INTO news (title, description) VALUES (:title, :description)";
        $query = $this->pdo->prepare($sql);
        $ok = $query->execute($news->toDbArray());
        if (!$ok) { throw new \RuntimeException('Insert failed'); }
        return (int)$this->pdo->lastInsertId();
    }

    public function update(News $news): bool
    {
        if ($news->getId() === null) { throw new \InvalidArgumentException('ID required for update'); }

        $sql = "UPDATE news SET title=:title, description=:description WHERE id=:id";
        $data = $news->toDbArray();
        $data['id'] = $news->getId();

        $query = $this->pdo->prepare($sql);
        return $query->execute($data);
    }

    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare("DELETE FROM news WHERE id=:id");
        return $query->execute([':id' => $id]);
    }
}
