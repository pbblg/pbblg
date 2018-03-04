<?php

namespace App\Domain\AccessToken;

use T4webDomain\Entity;

class AccessToken extends Entity
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $createdDt;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getCreatedDt(): string
    {
        return $this->createdDt;
    }

    /**
     * @param int $length
     * @return string
     */
    public static function generate($length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}