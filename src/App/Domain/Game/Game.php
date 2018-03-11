<?php

namespace App\Domain\Game;

use T4webDomain\Entity;

class Game extends Entity
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var int
     */
    protected $ownerId;

    /**
     * @var string
     */
    protected $createdDt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * @return string
     */
    public function getCreatedDt(): string
    {
        return $this->createdDt;
    }
}
