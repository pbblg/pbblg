<?php
namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Server implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface $conn)
    {
        echo sprintf("[%s] client connected", date('Y-m-d H:i:s')) . PHP_EOL . PHP_EOL;
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo sprintf("[%s] request: %s", date('Y-m-d H:i:s'), $msg) . PHP_EOL;
        $from->send('pong');
        echo sprintf("[%s] response: %s", date('Y-m-d H:i:s'), 'pong') . PHP_EOL . PHP_EOL;
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo sprintf("[%s] client go offline", date('Y-m-d H:i:s')) . PHP_EOL . PHP_EOL;
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}
