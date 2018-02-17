<?php
namespace App\WebSocket;

class Client
{
    public function send()
    {
        \Ratchet\Client\connect('ws://localhost:8088')->then(function($conn) {
            $conn->on('message', function($msg) use ($conn) {
                $conn->close();
            });

            $conn->send('{"id":1, "method": "ping"}');
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }
}
