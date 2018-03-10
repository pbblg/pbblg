<?php

namespace App\Command\Register;

class RegisterCommandContext
{
    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $passwordAgain;

    /**
     * @param string $userName
     * @param string $password
     * @param string $passwordAgain
     */
    public function __construct(string $userName, string $password, string $passwordAgain)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->passwordAgain = $passwordAgain;
    }

    public static function fromData(array $data)
    {
        return new self($data['username'], $data['password'], $data['password-again']);
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getPasswordAgain(): string
    {
        return $this->passwordAgain;
    }
}
