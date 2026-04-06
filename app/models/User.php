<?php
declare(strict_types=1);

namespace App\Models;

final class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $createdAt,
        public ?string $updatedAt,
        public ?string $deletedAt,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int)($row['id'] ?? 0),
            (string)($row['name'] ?? ''),
            (string)($row['email'] ?? ''),
            isset($row['created_at']) ? (string)$row['created_at'] : null,
            isset($row['updated_at']) ? (string)$row['updated_at'] : null,
            isset($row['deleted_at']) ? (string)$row['deleted_at'] : null,
        );
    }
}

