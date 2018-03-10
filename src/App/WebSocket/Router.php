<?php

namespace App\WebSocket;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Router\Route;
use App\WebSocket\Action\ParamsValidator;
use App\WebSocket\Exception\InternalErrorException;
use App\WebSocket\Exception\MethodNotFoundException;

class Router implements RouterInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function addRoute(Route $route)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function match(ServerRequestInterface $request)
    {
        $method = $request->getAttribute('method');
        if (!array_key_exists($method, $this->config)) {
            throw new MethodNotFoundException("Method \'$method\' not found");
        }

        if (!array_key_exists('handler', $this->config[$method])) {
            throw new InternalErrorException("Method \'$method\' have not handler");
        }

        if (array_key_exists('paramsValidator', $this->config[$method])) {
            $paramsValidator = $this->config[$method]['paramsValidator'];
        } else {
            $paramsValidator = ParamsValidator::class;
        }

        if (array_key_exists('params', $this->config[$method])) {
            $paramsConfig = $this->config[$method]['params'];
        } else {
            $paramsConfig = [];
        }

        return RouteResult::fromRoute(
            new Route(
                $method,
                $this->config[$method]['handler'],
                [],
                $method
            ),
            [
                'paramsValidator' => $paramsValidator,
                'paramsConfig' => $paramsConfig,
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function generateUri($name, array $substitutions = [], array $options = [])
    {
        return '';
    }
}
