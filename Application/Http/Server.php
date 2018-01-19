<?php

namespace Game\Application\Http;

use Zend\Stratigility\MiddlewarePipe;
use Game\Application\Application;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Server extends AbstractServer
{
    /**
     * {@inheritdoc}
     */
    protected function getMiddleware(Application $app)
    {
        $pipe = new MiddlewarePipe;
        $pipe->raiseThrowables();

//        $path = parse_url($app->url(), PHP_URL_PATH);
//        $errorDir = __DIR__.'/../../error';

//        $pipe->pipe($path, new HandleErrors($errorDir, $app->make('log'), $app->inDebugMode()));
//        $pipe->pipe($path, $app->make('Game\Application\Http\Middleware\ParseJsonBody'));
//        $pipe->pipe($path, $app->make('Game\Application\Http\Middleware\StartSession'));
//        $pipe->pipe($path, $app->make('Game\Application\Http\Middleware\RememberFromCookie'));
//        $pipe->pipe($path, $app->make('Game\Application\Http\Middleware\AuthenticateWithSession'));
//        $pipe->pipe($path, $app->make('Game\Application\Http\Middleware\SetLocale'));

//        event(new ConfigureMiddleware($pipe, $path, $this));

//        $pipe->pipe(
//            $path,
//            $app->make('Game\Application\Http\Middleware\DispatchRoute', ['routes' => $app->make('flarum.forum.routes')])
//        );

        $pipe->pipe('/', function(Request $request, Response $response, callable $out = null){
//            $response = new Response;
            $response->getBody()->write('Hello world!');
            return $response;
        });

        return $pipe;

    }
}