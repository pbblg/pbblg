<?php

namespace App\Domain\AccessToken;

use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\Domain\User;

class Generator
{
    /**
     * @var RepositoryInterface
     */
    private $accessTokenRepository;

    /**
     * @var RepositoryInterface
     */
    private $userRepository;

    /**
     * Generator constructor.
     * @param RepositoryInterface $accessTokenRepository
     * @param RepositoryInterface $userRepository
     */
    public function __construct(RepositoryInterface $accessTokenRepository, RepositoryInterface $userRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username
     * @return AccessToken|null
     */
    public function generateForUserName($username)
    {
        $user = $this->userRepository->find(['name_equalTo' => $username]);

        if (!$user) {
            return;
        }

        return $this->generateForUser($user);
    }

    /**
     * @param User\User $user
     * @return AccessToken
     */
    public function generateForUser(User\User $user)
    {
        $accessToken = new AccessToken([
            'token' => AccessToken::generate(),
            'userId' => $user->getId()
        ]);

        return $this->accessTokenRepository->add($accessToken);
    }
}