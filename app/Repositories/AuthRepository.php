<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;
use RuntimeException;
use Throwable;

final class AuthRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** @return array<string,mixed>|null */
    public function findActiveByEmail(string $email): ?array
    {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, name, email, password, created_at, updated_at, deleted_at
                 FROM users
                 WHERE email = :email AND deleted_at IS NULL
                 LIMIT 1'
            );
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch();

            return is_array($row) ? $row : null;
        } catch (Throwable $e) {
            throw new RuntimeException('Repository error finding user by email', 0, $e);
        }
    }
}

