<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Diactoros\Response\RedirectResponse;
use T4webDomainInterface\Infrastructure\RepositoryInterface;

class LogoutAction implements ServerMiddlewareInterface
{
    /**
     * @var RepositoryInterface
     */
    private $accessTokenRepository;

    /**
     * LogoutAction constructor.
     * @param RepositoryInterface $accessTokenRepository
     */
    public function __construct(RepositoryInterface $accessTokenRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if ($session->has(UserInterface::class)) {
            $session->clear();

            $cookie = $request->getCookieParams();

            if (isset($cookie['access_token'])) {
                $accessToken = $this->accessTokenRepository->find(['token' => $cookie['access_token']]);

                if ($accessToken) {
                    $this->accessTokenRepository->remove($accessToken);
                }
            }
        }

        return new RedirectResponse('/');
    }
}
