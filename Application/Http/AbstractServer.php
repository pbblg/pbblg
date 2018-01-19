<?php

namespace Game\Application\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Server as ZendServer;
use Zend\Stratigility\NoopFinalHandler;
use Zend\Stratigility\MiddlewareInterface;
use Game\Application\Application;
use Game\Application\AbstractServer as BaseAbstractServer;

abstract class AbstractServer extends BaseAbstractServer
{
    /**
     * @var Application
     */
    protected $app;

    public function listen()
    {
        ZendServer::createServer(
            $this,
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        )->listen(new NoopFinalHandler());
    }

    /**
     * @param Application $app
     * @return MiddlewareInterface
     */
    abstract protected function getMiddleware(Application $app);

    /**
     * Use as PSR-7 middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $out
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out)
    {
        $app = $this->getApp();

        $middleware = $this->getMiddleware($app);

        return $middleware($request, $response, $out);
    }
}