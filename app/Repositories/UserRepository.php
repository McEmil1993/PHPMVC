<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use PDO;
use RuntimeException;
use Throwable;

final class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** @return User[] */
    public function all(): array
    {
        try {
            $stmt = $this->pdo->query(
                'SELECT id, name, email, created_at, updated_at, deleted_at
                 FROM users
                 WHERE deleted_at IS NULL
                 ORDER BY id DESC'
            );

            $rows = $stmt->fetchAll();
            return array_map(static fn (array $row): User => User::fromRow($row), $rows);
        } catch (Throwable $e) {
            throw new RuntimeException('Repository error fetching users', 0, $e);
        }
    }

    public function find(int $id): ?User
    {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, name, email, created_at, updated_at, deleted_at
                 FROM users
                 WHERE id = :id AND deleted_at IS NULL
                 LIMIT 1'
            );
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();

            return $row ? User::fromRow($row) : null;
        } catch (Throwable $e) {
            throw new RuntimeException('Repository error finding user', 0, $e);
        }
    }

    public function create(string $name, string $email, string $passwordHash): User
    {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (name, email, password, created_at, updated_at)
                 VALUES (:name, :email, :password, NOW(), NOW())'
            );
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
            ]);

            $id = (int)$this->pdo->lastInsertId();
            $user = $this->find($id);
            if ($user === null) {
                throw new RuntimeException('Failed to load created user');
            }

            return $user;
        } catch (Throwable $e) {
            throw new RuntimeException('Repository error creating user', 0, $e);
        }
    }

    /** @param array{name?:string,email?:string} $fields */
    public function update(int $id, array $fields): ?User
    {
        try {
            $set = [];
            $params = ['id' => $id];

            if (array_key_exists('name', $fields)) {
                $set[] = 'name = :name';
                $params['name'] = $fields['name'];
            }
            if (array_key_exists('email', $fields)) {
                $set[] = 'email = :email';
                $params['email'] = $fields['email'];
            }

            $set[] = 'updated_at = NOW()';

            $sql = 'UPDATE users SET ' . implode(', ', $set) . ' WHERE id = :id AND deleted_at IS NULL';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $this->find($id);
        } catch (Throwable $e) {
            throw new RuntimeException('Repository error updating user', 0, $e);
        }
    }

    public function softDelete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE users
                 SET deleted_at = NOW(), updated_at = NOW()
                 WHERE id = :id AND deleted_at IS NULL'
            );
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (Throwable $e) {
            throw new RuntimeException('Repository error deleting user', 0, $e);
        }
    }
}
