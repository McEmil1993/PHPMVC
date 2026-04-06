<?php
declare(strict_types=1);

namespace App\Requests;

use InvalidArgumentException;
use Throwable;

abstract class BaseJsonRequest
{
    /** @return array<string, mixed> */
    protected function jsonBody(): array
    {
        try {
            $raw = file_get_contents('php://input');
            if ($raw === false || trim($raw) === '') {
                return [];
            }

            $decoded = json_decode($raw, true);
            if (!is_array($decoded)) {
                throw new InvalidArgumentException('Invalid JSON body');
            }

            return $decoded;
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new InvalidArgumentException('Unable to read request body');
        }
    }

    protected function requireString(array $data, string $key): string
    {
        try {
            $value = $data[$key] ?? null;
            if (!is_string($value) || trim($value) === '') {
                throw new InvalidArgumentException("{$key} is required");
            }

            return trim($value);
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new InvalidArgumentException("{$key} is invalid");
        }
    }

    protected function optionalString(array $data, string $key): ?string
    {
        try {
            if (!array_key_exists($key, $data)) {
                return null;
            }

            $value = $data[$key];
            if ($value === null) {
                return null;
            }
            if (!is_string($value)) {
                throw new InvalidArgumentException("{$key} must be a string");
            }

            $trimmed = trim($value);
            return $trimmed === '' ? null : $trimmed;
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new InvalidArgumentException("{$key} is invalid");
        }
    }

    protected function validateEmail(string $email): string
    {
        try {
            $normalized = strtolower(trim($email));
            if (!filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('email is invalid');
            }
            return $normalized;
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new InvalidArgumentException('email is invalid');
        }
    }
}

