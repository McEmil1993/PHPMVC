<?php
declare(strict_types=1);

namespace App\Requests;

use InvalidArgumentException;

final class ValidationException extends InvalidArgumentException
{
    /** @param array<string,string> $errors */
    public function __construct(private array $errors, string $message = 'Validation failed')
    {
        parent::__construct($message);
    }

    /** @return array<string,string> */
    public function errors(): array
    {
        return $this->errors;
    }
}

