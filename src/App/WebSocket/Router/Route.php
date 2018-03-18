<?php

namespace App\WebSocket\Router;

use Fig\Http\Message\RequestMethodInterface as RequestMethod;
use Webimpress\HttpMiddlewareCompatibility\MiddlewareInterface;
use Zend\Expressive\Router\Exception;
use Zend\Expressive\Router\Route as ZendRoute;

class Route
{
    /**
     * @var string
     */
    private $paramsValidator;

    /**
     * @var array
     */
    private $paramsConfig;

    /**
     * @var string
     */
    private $handler;

    public function __construct($paramsValidator, array $paramsConfig, $handler)
    {
        $this->paramsValidator = $paramsValidator;
        $this->paramsConfig = $paramsConfig;
        $this->handler = $handler;
    }

    /**
     * @return string
     */
    public function getParamsValidator(): string
    {
        return $this->paramsValidator;
    }

    /**
     * @return array
     */
    public function getParamsConfig(): array
    {
        return $this->paramsConfig;
    }

    /**
     * @return string
     */
    public function getHandler(): string
    {
        return $this->handler;
    }
}
