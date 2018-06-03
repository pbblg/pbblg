<?php

use App\WebSocket\Action;
use Zend\Validator;

return [
    'routes' => [
        'ping' => [
            // must implement ParamsValidatorInterface
            // 'paramsValidator' => Action\Ping\PingParamsValidator::class,
            'handler' => Action\Ping\PingHandler::class,
        ],
        'getMyself' => [
            'handler' => Action\GetMyself\GetMyselfHandler::class,
        ],
        'getOnlineUsers' => [
            'handler' => Action\GetOnlineUsers\GetOnlineUsersHandler::class,
        ],
        'getGames' => [
            'handler' => Action\GetGames\GetGamesHandler::class,
        ],
        'newGame' => [
            'handler' => Action\NewGame\NewGameHandler::class,
        ],
        'joinGame' => [
            'handler' => Action\JoinGame\JoinGameHandler::class,
            'params' => [
                'gameId' => [
                    'required' => true,
                    'validators' => [
                        ['name' => Validator\NotEmpty::class],
                        ['name' => Validator\GreaterThan::class, 'options' => ['min' => 0]],
                    ],
                ],
            ],
        ],
        'exitGame' => [
            'handler' => Action\ExitGame\ExitGameHandler::class,
        ],
        'startGame' => [
            'handler' => Action\StartGame\StartGameHandler::class,
            'params' => [
                'gameId' => [
                    'required' => true,
                    'validators' => [
                        ['name' => Validator\NotEmpty::class],
                        ['name' => Validator\GreaterThan::class, 'options' => ['min' => 0]],
                    ],
                ],
            ],
        ],
        'send' => [
            'handler' => Action\Send\SendHandler::class,
            'paramsValidator' => Action\Send\SendParamsValidator::class,
        ],
    ],
];
