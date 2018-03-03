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
