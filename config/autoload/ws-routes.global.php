<?php

use App\WebSocket\Action;

return [
    'routes' => [
        'ping' => [
            'handler' => Action\Ping\PingHandler::class,
        ],
        'newGame' => [
            'handler' => Action\NewGame\NewGameHandler::class,
        ],
        'joinGame' => [
            'handler' => Action\JoinGame\JoinGameHandler::class,
        ],
    ],
];
