<?php
declare(strict_types=1);

namespace App\Requests;

use InvalidArgumentException;
use Throwable;

final class UpdateUserRequest extends BaseJsonRequest
{
    /** @return array{name?:string,email?:string} */
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

            if ($errors !== []) {
                throw new ValidationException($errors);
            }

            return [
                'name' => (string)$name,
                'email' => (string)$email,
            ];
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new InvalidArgumentException('Invalid request');
        }
    }
}
