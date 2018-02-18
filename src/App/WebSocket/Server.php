<?php
namespace App\WebSocket;

use ArrayObject;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Expressive\Middleware\NotFoundHandler;
use Zend\Stratigility\Middleware\ErrorHandler;
use App\Domain\AccessToken\Repository;

class Server implements MessageComponentInterface
{
    /**
     * @var Emitter
     */
    private $emitter;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ConnectionInterface[]
     */
    private static $authorizedConnections = [];

    /**
     * @var ConnectionInterface[]
     */
    private static $anonymousConnections = [];

    /**
     * @var bool
     */
    private static $isRunning = false;

    public function __construct()
    {
        $this->container = require 'config/ws-container.php';
        self::$isRunning = true;
    }

    /**
     * @return bool
     */
    public static function isRunning(): bool
    {
        return self::$isRunning;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->saveConnection($conn);

        echo sprintf("[%s] client connected", date('Y-m-d H:i:s')) . PHP_EOL . PHP_EOL;
    }

    private function saveConnection(ConnectionInterface $conn)
    {
        $cookiesHeader = $conn->httpRequest->getHeader('Cookie');
        if(count($cookiesHeader) == 0) {
            self::$anonymousConnections[] = $conn;
            return;
        }

        $cookies = \GuzzleHttp\Psr7\parse_header($cookiesHeader)[0];
        if (!array_key_exists('access_token', $cookies)) {
            self::$anonymousConnections[] = $conn;
            return;
        }

        $token = $cookies['access_token'];

        /** @var Repository $accessTokenRepository */
        $accessTokenRepository = $this->container->get(Repository::class);
        $accessToken = $accessTokenRepository->fetch($token);

        if (!$accessToken) {
            self::$anonymousConnections[] = $conn;
            return;
        }

        self::$authorizedConnections[$accessToken->getUserId()] = $conn;
    }

    /**
     * @return int
     */
    public function getAuthorizedConnectionsCount()
    {
        return count(self::$authorizedConnections);
    }

    /**
     * @return int
     */
    public function getAnonymousConnectionsCount()
    {
        return count(self::$anonymousConnections);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo sprintf("[%s] request: %s", date('Y-m-d H:i:s'), $msg) . PHP_EOL;

        $application = $this->getApplication();

        $application->pipe(ErrorHandler::class);
        $application->pipe(Middleware\JsonRpcMiddleware::class);
        $application->pipeRoutingMiddleware();
        $application->pipe(Middleware\ParamsValidatorMiddleware::class);
        $application->pipe(Middleware\DispatchMiddleware::class);
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

        $response = $this->emitter->getResponse();

        if ($response instanceof SenderResponse) {
            self::send($response->getReceivers(),$response->getMessage());
            $from->send('ok');
        } else {
            $responseBody = $response->getBody();
            $responseBody->rewind();
            $rawResponse = $responseBody->getContents();

            $responseBody->close();

            $from->send($rawResponse);
            echo sprintf("[%s] response: %s", date('Y-m-d H:i:s'), $rawResponse) . PHP_EOL . PHP_EOL;
        }
    }

    /**
     * @param array $receivers
     * @param string $message
     */
    public static function send(array $receivers, $message)
    {
        if (empty($receivers)) {
            foreach (self::$anonymousConnections as $anonymousConnection) {
                $anonymousConnection->send($message);
                echo sprintf(
                        "[%s] response to anonym: %s",
                        date('Y-m-d H:i:s'),
                        $message
                    ) . PHP_EOL . PHP_EOL;
            }

            foreach (self::$authorizedConnections as $userId=>$authorizedConnection) {
                $authorizedConnection->send($message);
                echo sprintf(
                        "[%s] response to #%s: %s",
                        date('Y-m-d H:i:s'),
                        $userId,
                        $message
                    ) . PHP_EOL . PHP_EOL;
            }
        } else {
            foreach ($receivers as $userId) {
                if (array_key_exists($userId, self::$authorizedConnections)) {
                    self::$authorizedConnections[$userId]->send($message);
                    echo sprintf(
                        "[%s] response to #%s: %s",
                        date('Y-m-d H:i:s'),
                        $userId,
                            $message
                    ) . PHP_EOL . PHP_EOL;
                }
            }
        }
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
        $this->removeConnection($conn);
        echo sprintf("[%s] client go offline", date('Y-m-d H:i:s')) . PHP_EOL . PHP_EOL;
    }

    private function removeConnection(ConnectionInterface $conn)
    {
        foreach (self::$anonymousConnections as $key=>$anonymousConnection) {
            if ($anonymousConnection === $conn) {
                unset(self::$anonymousConnections[$key]);
                return;
            }
        }

        foreach (self::$authorizedConnections as $key=>$authorizedConnection) {
            if ($authorizedConnection === $conn) {
                unset(self::$authorizedConnections[$key]);
                return;
            }
        }
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
        // Это сделано специально
        $container = require 'config/ws-container.php';
        $config = $container->has('config') ? $container->get('config') : [];
        $config = $config instanceof ArrayObject ? $config->getArrayCopy() : $config;

        $router = new Router($config['routes']);

        $delegate = $container->get('Zend\Expressive\Delegate\DefaultDelegate');

        $this->emitter = new Emitter();

        $app = new Application($router, $container, $delegate, $this->emitter);

        if (empty($config['zend-expressive']['programmatic_pipeline'])) {
            $app->injectRoutesFromConfig($config);
            $app->injectPipelineFromConfig($config);
        }

        return $app;
    }
}
