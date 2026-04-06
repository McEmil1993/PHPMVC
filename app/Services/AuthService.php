<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use PDO;
use RuntimeException;
use Throwable;

final class AuthService
{
    private AuthRepository $authRepository;

    public function __construct(PDO $pdo)
    {
        $this->authRepository = new AuthRepository($pdo);
    }

    /** @return array{user:?User, reason:?string} reason: not_found|wrong_password|null */
    public function attempt(string $email, string $password): array
    {
        try {
            $row = $this->authRepository->findActiveByEmail($email);
            if ($row === null) {
                return ['user' => null, 'reason' => 'not_found'];
            }

            $hash = (string)($row['password'] ?? '');
            if ($hash === '' || !password_verify($password, $hash)) {
                return ['user' => null, 'reason' => 'wrong_password'];
            }

            return ['user' => User::fromRow($row), 'reason' => null];
        } catch (Throwable $e) {
            throw new RuntimeException('Service error attempting login', 0, $e);
        }
    }
}
