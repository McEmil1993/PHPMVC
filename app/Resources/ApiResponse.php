<?php
declare(strict_types=1);

namespace App\Resources;

use Throwable;

final class ApiResponse
{
    public static function success(string $message = '', mixed $data = null): array
    {
        try {
            return [
                'success' => true,
                'message' => $message,
                'data' => $data ?? (object)[],
            ];
        } catch (Throwable $e) {
            return [
                'success' => true,
                'message' => $message,
                'data' => (object)[],
            ];
        }
    }

    public static function error(string $message = '', mixed $data = null): array
    {
        try {
            return [
                'success' => false,
                'message' => $message,
                'data' => $data ?? (object)[],
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => $message,
                'data' => (object)[],
            ];
        }
    }
}

