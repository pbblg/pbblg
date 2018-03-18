<?php

namespace App\WebSocket\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;
use Zend\Diactoros\Response\JsonResponse;
use App\WebSocket\Exception\InternalErrorException;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Action\SpecialHandlerInterface;
use App\WebSocket\Router\Route;

class DispatchMiddleware implements ServerMiddlewareInterface
{
    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function __construct(
        ContainerInterface $container = null
    ) {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /** @var Route $routeResult */
        $routeResult = $request->getAttribute(Route::class, false);
        if (! $routeResult) {
            return $delegate->process($request);
        }

        $handler = $routeResult->getHandler();

        if (is_string($handler)) {
            $handler = $this->container->get($handler);
        }

        if ($handler instanceof SpecialHandlerInterface) {
            return $handler->handle($request);
        }

        if (!$handler instanceof ActionHandlerInterface) {
            throw new InternalErrorException('Bad handler');
        }

        return new JsonResponse([
            'id' => $request->getAttribute('id'),
            'result' => $handler->handle($request)
        ]);
    }
}
