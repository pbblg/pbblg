<?php
namespace App\WebSocket;

use App\WebSocket\Event\AbstractEvent;
use App\WebSocket\Exception\InternalErrorException;

class Client
{
    /**
     * @var string
     */
    private $secret;

    /**
     * @param string $secret
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function send(array $receivers, AbstractEvent $event)
    {
        $jsonOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES;

        // мы внутри сокет-свервера, нет смысла коннектиться снаружи
        if (Server::isRunning()) {
            $receivers = array_unique($receivers);
            $message = json_encode($event->toArray(), $jsonOptions);

            Server::send($receivers, $message);
            return;
        }

        $secret = $this->secret;

        \Ratchet\Client\connect('ws://localhost:8088')->then(
            function ($conn) use ($receivers, $event, $secret, $jsonOptions) {
                $conn->on('message', function ($msg) use ($conn) {
                    $conn->close();
                });

                $message = [
                    'id' => 1,
                    'method' => 'send',
                    'params' => [
                        'secret' => $secret,
                        'receivers' => $receivers,
                        'message' => $event->toArray()
                    ],
                ];

                $conn->send(json_encode($message, $jsonOptions));
            },
            function ($e) {
                echo "Could not connect: {$e->getMessage()}\n";
            }
        );
    }

    public function getOnlineUsers()
    {
        if (!Server::isRunning()) {
            throw new InternalErrorException('Websocket server not running.');
        }
        return Server::getAuthorizedUserIds();
    }
}
