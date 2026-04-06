<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use RuntimeException;
use Throwable;

final class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /** @return User[] */
    public function list(): array
    {
        try {
            return $this->userRepository->all();
        } catch (Throwable $e) {
            throw new RuntimeException('Service error listing users', 0, $e);
        }
    }

    /** @param array{name:string,email:string,password:string} $data */
    public function create(array $data): User
    {
        try {
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            return $this->userRepository->create($data['name'], $data['email'], $passwordHash);
        } catch (Throwable $e) {
            throw new RuntimeException('Service error creating user', 0, $e);
        }
    }

    /** @param array{name?:string,email?:string} $data */
    public function update(int $id, array $data): ?User
    {
        try {
            return $this->userRepository->update($id, $data);
        } catch (Throwable $e) {
            throw new RuntimeException('Service error updating user', 0, $e);
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->userRepository->softDelete($id);
        } catch (Throwable $e) {
            throw new RuntimeException('Service error deleting user', 0, $e);
        }
    }
}
