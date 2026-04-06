<?php
declare(strict_types=1);

namespace Bootstrap;

use RuntimeException;

final class View
{
    /** @param array<string,mixed> $data */
    public static function render(string $view, array $data = [], int $statusCode = 200): void
    {
        $file = BASE_PATH . '/app/views/' . ltrim($view, '/') . '.php';
        if (!is_file($file)) {
            throw new RuntimeException("View not found: {$view}");
        }

        http_response_code($statusCode);
        header('Content-Type: text/html; charset=utf-8');

        extract($data, EXTR_SKIP);
        require $file;
    }
}
