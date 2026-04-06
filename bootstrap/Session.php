<?php
declare(strict_types=1);

namespace Bootstrap;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_start();
    }

    public static function regenerate(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }

        session_regenerate_id(true);
    }

    public static function set(string $key, mixed $value): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }

        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }

        return $_SESSION[$key] ?? $default;
    }

    public static function forget(string $key): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }

        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }

        $_SESSION = [];
        session_destroy();
    }
}

