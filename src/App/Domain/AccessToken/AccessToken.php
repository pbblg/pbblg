<?php

namespace App\Domain\AccessToken;


class AccessToken
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $userId;

    /**
     * AccessToken constructor.
     * @param string $id
     * @param int $userId
     */
    public function __construct(string $id, int $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
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