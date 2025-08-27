<?php

namespace App\Model;

final class News
{
    public function __construct(
        private ?int $id,
        private string $title,
        private string $description,
        private ?\DateTimeImmutable $createdAt = null,
        private ?\DateTimeImmutable $updatedAt = null,
    ) {}

    public static function createNew(string $title, string $description): self
    {
        return new self(null, $title, $description);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int)$row['id'],
            title: (string)$row['title'],
            description: $row['description'] !== null ? (string)$row['description'] : '',
            createdAt: isset($row['created_at']) ? new \DateTimeImmutable((string)$row['created_at']) : null,
            updatedAt: !empty($row['updated_at']) ? new \DateTimeImmutable((string)$row['updated_at']) : null,
        );
    }

    public function toDbArray(): array
    {
        return [
            'title'   => $this->title,
            'description' => $this->description,
        ];
    }
}
