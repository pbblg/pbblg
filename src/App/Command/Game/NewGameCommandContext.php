<?php

namespace App\Command\Game;

use App\Domain\User\User;

class NewGameCommandContext
{
    /**
     * @var User|null
     */
    private $user;

    /**
     * NewGameCommandContext constructor.
     * @param User $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}