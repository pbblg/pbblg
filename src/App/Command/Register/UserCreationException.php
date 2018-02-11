<?php

namespace App\Command\Register;

use RuntimeException;
use Throwable;

class UserCreationException extends RuntimeException
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            'Can not save user to database',
            $code,
            $previous
        );
    }
}