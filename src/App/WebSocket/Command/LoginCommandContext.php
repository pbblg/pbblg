<?php

namespace App\WebSocket\Command;

class LoginCommandContext
{
    /**
     * @var string
     */
    private $userName;

    /**
     * @param string $userName
     */
    public function __construct(string $userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }
}
