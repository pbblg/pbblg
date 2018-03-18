<?php

namespace App\WebSocket\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\WebSocket\Router\Router;
use App\WebSocket\Router\Route;

class RouteMiddleware implements ServerMiddlewareInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /** @var Route $route */
        $route = $this->router->match($request);

        return $delegate->process($request->withAttribute(Route::class, $route));
    }
}
