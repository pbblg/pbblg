<?php
namespace App\WebSocket;

use ArrayObject;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Zend\Expressive\Application;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Middleware\ImplicitOptionsMiddleware;
use Zend\Expressive\Middleware\NotFoundHandler;
use Zend\Stratigility\Middleware\ErrorHandler;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

class Server implements MessageComponentInterface
{
    /**
     * @var Emitter
     */
    private $emitter;

    public function onOpen(ConnectionInterface $conn)
    {
        echo sprintf("[%s] client connected", date('Y-m-d H:i:s')) . PHP_EOL . PHP_EOL;
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        //if ($msg == 'ping') {
            echo sprintf("[%s] request: %s", date('Y-m-d H:i:s'), $msg) . PHP_EOL;
           // $from->send('pong');
           // echo sprintf("[%s] response: %s", date('Y-m-d H:i:s'), 'pong') . PHP_EOL . PHP_EOL;
            //return;
        //}*/

        $application = $this->getApplication();

        $application->pipe(ErrorHandler::class);
        $application->pipe(Middleware\JsonRpcMiddleware::class);
        $application->pipeRoutingMiddleware();
        $application->pipe(ImplicitHeadMiddleware::class);
        $application->pipe(ImplicitOptionsMiddleware::class);
        $application->pipe(UrlHelperMiddleware::class);
        $application->pipeDispatchMiddleware();
        $application->pipe(NotFoundHandler::class);

        $request = new ServerRequest(
            $_SERVER,
            [],
            '/',
            'POST',
            $this->createBodyStream($msg),
            ['Content-Type' => 'application/json']
        );

        $application->run($request, new Response());

        $responseBody = $this->emitter->getResponse()->getBody();
        $responseBody->rewind();
        $rawResponse = $responseBody->getContents();

        $responseBody->close();

        $from->send($rawResponse);
        echo sprintf("[%s] response: %s", date('Y-m-d H:i:s'), $rawResponse) . PHP_EOL . PHP_EOL;
    }

    private function createBodyStream($reactRequest)
    {
        $body = fopen('php://temp', 'w+');
        fwrite($body, $reactRequest);
        fseek($body, 0);
        return $body;
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo sprintf("[%s] client go offline", date('Y-m-d H:i:s')) . PHP_EOL . PHP_EOL;
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo sprintf(
            "[%s] client error: %s",
            date('Y-m-d H:i:s'),
            $e->getMessage() . PHP_EOL . $e->getTraceAsString()
        ) . PHP_EOL;
        $conn->close();
    }

    private function getApplication()
    {
        $this->container = require 'config/ws-container.php';

        $config = $this->container->has('config') ? $this->container->get('config') : [];
        $config = $config instanceof ArrayObject ? $config->getArrayCopy() : $config;

        $router = new Router($config['routes']);

        $delegate = $this->container->has('Zend\Expressive\Delegate\DefaultDelegate')
            ? $this->container->get('Zend\Expressive\Delegate\DefaultDelegate')
            : null;

        $this->emitter = new Emitter();

        $app = new Application($router, $this->container, $delegate, $this->emitter);

        if (empty($config['zend-expressive']['programmatic_pipeline'])) {
            $app->injectRoutesFromConfig($config);
            $app->injectPipelineFromConfig($config);
        }

        return $app;
    }
}
