<?php

namespace Game\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Server as ZendServer;
use Zend\Stratigility\NoopFinalHandler;
use Zend\Stratigility\MiddlewareInterface;

abstract class AbstractServer
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

    /**
     * @return Application
     */
    public function getApp()
    {
        if ($this->app !== null) {
            return $this->app;
        }

        date_default_timezone_set('UTC');

        $app = new Application();

//        $app->setService('config', $this->config);

//        $this->registerLogger($app);

//        $this->registerCache($app);

//        $app->register('Game\Application\Database\DatabaseServiceProvider');
//        $app->register('Game\Application\Settings\SettingsServiceProvider');
//        $app->register('Game\Application\Locale\LocaleServiceProvider');
//        $app->register('Game\Application\Bus\BusServiceProvider');
//        $app->register('Game\Application\Filesystem\FilesystemServiceProvider');
//        $app->register('Game\Application\View\ViewServiceProvider');

        $app->boot();

        $this->app = $app;

        return $app;
    }


}