<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Webimpress\HttpMiddlewareCompatibility\HandlerInterface as DelegateInterface;
use Webimpress\HttpMiddlewareCompatibility\MiddlewareInterface;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Authentication\UserInterface;
use App\ViewHelper\IsAuthorizedHelper;

use const Webimpress\HttpMiddlewareCompatibility\HANDLER_METHOD;

class AuthorizedUserHelperMiddleware implements MiddlewareInterface
{
    /**
     * @var IsAuthorizedHelper
     */
    private $helper;

    /**
     * @param IsAuthorizedHelper $helper
     */
    public function __construct(IsAuthorizedHelper $helper)
    {
        $this->helper = $helper;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if ($session->has(UserInterface::class)) {
            $this->helper->setAuthorizedUser($session->get(UserInterface::class));
        }

        return $delegate->{HANDLER_METHOD}($request);
    }
}
