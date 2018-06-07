<?php

namespace App\WebSocket\Command;

use App\Domain\User\User;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Client;
use App\Domain\AccessToken\Generator;
use Dflydev\FigCookies\SetCookie;
use App\WebSocket\Event\UserLoggedIn;

class LoginCommand
{
    /**
     * @var Generator
     */
    private $accessTokenGenerator;

    /**
     * @var Client
     */
    private $webSocketClient;

    /**
     * @var RepositoryInterface
     */
    private $userRepository;

    /**
     * LoginCommand constructor.
     * @param Generator $accessTokenGenerator
     * @param Client $webSocketClient
     * @param RepositoryInterface $userRepository
     */
    public function __construct(
        Generator $accessTokenGenerator,
        Client $webSocketClient,
        RepositoryInterface $userRepository
    ) {
        $this->accessTokenGenerator = $accessTokenGenerator;
        $this->webSocketClient = $webSocketClient;
        $this->userRepository = $userRepository;
    }

    /**
     * @param LoginCommandContext $context
     * @return SetCookie
     */
    public function handle(LoginCommandContext $context): SetCookie
    {
        /** @var User $user */
        $user = $this->userRepository->find(['name_equalTo' => $context->getUserName()]);

        $accessToken = $this->accessTokenGenerator->generateForUserName($user->getName());
        $sessionCookie = SetCookie::create('access_token')
            ->withValue($accessToken->getToken())
            ->withPath(ini_get('session.cookie_path'));

        $this->webSocketClient->send([], new UserLoggedIn([
            'id' => $user->getId(),
            'name' => $user->getName()
        ]));

        return $sessionCookie;
    }
}
