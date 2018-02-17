<?php

namespace App\Domain\AccessToken;

use App\Domain\User;

class Generator
{
    /**
     * @var Repository
     */
    private $accessTokenRepository;

    /**
     * @var User\Repository
     */
    private $userRepository;

    /**
     * @param Repository $accessTokenRepository
     * @param User\Repository $userRepository
     */
    public function __construct(Repository $accessTokenRepository, User\Repository $userRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username
     * @return AccessToken
     */
    public function generateForUserName($username)
    {
        $user = $this->userRepository->fetchByName($username);
        return $this->generateForUser($user);
    }

    /**
     * @param User\User $user
     * @return AccessToken
     */
    public function generateForUser(User\User $user)
    {
        $id = AccessToken::generate();
        $accessToken = new AccessToken(AccessToken::generate(), $user->getId());

        return $this->accessTokenRepository->add($accessToken);
    }
}