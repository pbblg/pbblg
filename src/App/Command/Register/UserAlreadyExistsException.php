<?php

namespace App\Command\Register;

use RuntimeException;
use Throwable;

class UserAlreadyExistsException extends RuntimeException
{
    public function __construct(string $name, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf("User '%s' already registered", $name),
            $code,
            $previous
        );
    }
}
