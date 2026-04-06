<?php
declare(strict_types=1);

namespace App\Resources;

use App\Models\User;
use RuntimeException;
use Throwable;

final class UserResource
{
    public static function make(User $user): array
    {
        try {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->createdAt,
                'updated_at' => $user->updatedAt,
                'deleted_at' => $user->deletedAt,
            ];
        } catch (Throwable $e) {
            throw new RuntimeException('Resource error formatting user', 0, $e);
        }
    }

    /** @param User[] $users */
    public static function collection(array $users): array
    {
        try {
            return array_map(static fn (User $user): array => self::make($user), $users);
        } catch (Throwable $e) {
            throw new RuntimeException('Resource error formatting users', 0, $e);
        }
    }
}

