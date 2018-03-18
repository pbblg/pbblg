<?php

namespace App\WebSocket\Router;

use Psr\Http\Message\ServerRequestInterface;
use App\WebSocket\Action\ParamsValidator;
use App\WebSocket\Exception\InternalErrorException;
use App\WebSocket\Exception\MethodNotFoundException;

class Router
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
     * @param  ServerRequestInterface $request
     * @return Route
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

        return new Route(
            $paramsValidator,
            $paramsConfig,
            $this->config[$method]['handler']
        );
    }
}
