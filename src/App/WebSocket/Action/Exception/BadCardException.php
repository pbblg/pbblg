<?php

namespace App\WebSocket\Action\Exception;

use RuntimeException;

class BadCardException extends RuntimeException
{
    public function __construct($cardId)
    {
        parent::__construct(sprintf('You not have card #%s', $cardId));
    }
}
