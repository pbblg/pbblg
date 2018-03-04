<?php

namespace App\WebSocket\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\Domain\AccessToken\AccessToken;

use const Webimpress\HttpMiddlewareCompatibility\HANDLER_METHOD;

class AuthMiddleware implements MiddlewareInterface
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
     * AuthMiddleware constructor.
     * @param RepositoryInterface $accessTokenRepository
     * @param RepositoryInterface $userRepository
     */
    public function __construct(RepositoryInterface $accessTokenRepository, RepositoryInterface $userRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /** @var AccessToken $accessToken */
        $accessToken = $request->getAttribute('accessToken');

        if ($accessToken) {
            $user = $this->userRepository->find(['id' => $accessToken->getUserId()]);

            if ($user) {
                $request = $request->withAttribute('currentUser', $user);
            }
        }

        return $delegate->{HANDLER_METHOD}($request);
    }
}
