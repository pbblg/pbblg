<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Diactoros\Response\RedirectResponse;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\Domain\User\User;
use App\WebSocket\Client;
use App\WebSocket\Event\UserLoggedOut;

class LogoutAction implements ServerMiddlewareInterface
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
     * @var Client
     */
    private $webSocketClient;

    public function __construct(
        RepositoryInterface $accessTokenRepository,
        RepositoryInterface $userRepository,
        Client $webSocketClient
    ) {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRepository = $userRepository;
        $this->webSocketClient = $webSocketClient;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if ($session->has(UserInterface::class)) {
            $userIdentity = $session->get(UserInterface::class);
            $session->clear();

            $cookie = $request->getCookieParams();

            if (isset($cookie['access_token'])) {
                $accessToken = $this->accessTokenRepository->find(['token' => $cookie['access_token']]);

                if ($accessToken) {
                    $this->accessTokenRepository->remove($accessToken);
                }
            }

            /** @var User $user */
            $user = $this->userRepository->find(['name_equalTo' => $userIdentity['username']]);

            $this->webSocketClient->send([], new UserLoggedOut([
                'id' => $user->getId(),
                'name' => $user->getName()
            ]));
        }

        return new RedirectResponse('/');
    }
}
