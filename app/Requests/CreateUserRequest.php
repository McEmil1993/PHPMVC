<?php
declare(strict_types=1);

namespace App\Requests;

use InvalidArgumentException;
use Throwable;

final class CreateUserRequest extends BaseJsonRequest
{
    /** @return array{name:string,email:string,password:string} */
    public function validate(): array
    {
        try {
            $data = $this->jsonBody();
            $errors = [];

            $name = $this->optionalString($data, 'name');
            if ($name === null) {
                $errors['name'] = 'Name is required';
            }

            $emailInput = $this->optionalString($data, 'email');
            $email = null;
            if ($emailInput === null) {
                $errors['email'] = 'Email is required';
            } else {
                try {
                    $email = $this->validateEmail($emailInput);
                } catch (InvalidArgumentException) {
                    $errors['email'] = 'Email is invalid';
                }
            }

            $password = $this->optionalString($data, 'password');
            if ($password === null) {
                $errors['password'] = 'Password is required';
            } elseif (strlen($password) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }

            if ($errors !== []) {
                throw new ValidationException($errors);
            }

            return [
                'name' => (string)$name,
                'email' => (string)$email,
                'password' => (string)$password,
            ];
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new InvalidArgumentException('Invalid request');
        }
    }
}
