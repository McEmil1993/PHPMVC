<?php
declare(strict_types=1);

namespace App\Requests;

use InvalidArgumentException;
use Throwable;

final class LoginRequest extends BaseJsonRequest
{
    /** @return array{email:string,password:string} */
    public function validate(): array
    {
        try {
            $contentType = (string)($_SERVER['CONTENT_TYPE'] ?? '');

            $data = [];
            if (!empty($_POST)) {
                $data = $_POST;
            } elseif (str_contains($contentType, 'application/json')) {
                $data = $this->jsonBody();
            }

            $errors = [];

            $emailRaw = is_string($data['email'] ?? null) ? trim((string)$data['email']) : '';
            if ($emailRaw === '') {
                $errors['email'] = 'Email is required';
            } elseif (!filter_var(strtolower($emailRaw), FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email is invalid';
            }

            $passwordRaw = is_string($data['password'] ?? null) ? (string)$data['password'] : '';
            if (trim($passwordRaw) === '') {
                $errors['password'] = 'Password is required';
            }

            if ($errors !== []) {
                throw new ValidationException($errors);
            }

            $email = strtolower($emailRaw);
            $password = $passwordRaw;

            return [
                'email' => $email,
                'password' => $password,
            ];
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new InvalidArgumentException('Invalid request');
        }
    }
}
