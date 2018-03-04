<?php

return [
    'entity_map' => [
        'Game' => [
            'entityClass' => App\Domain\Game\Game::class,
            'table' => 'games',
            'primaryKey' => 'id',
            'columnsAsAttributesMap' => [
                'id' => 'id',
                'status' => 'status',
                'owner_id' => 'ownerId',
                'created_dt' => 'createdDt',
            ],
            'criteriaMap' => [
                'id' => 'id_equalTo',
            ]
        ],
        'AccessToken' => [
            'entityClass' => App\Domain\AccessToken\AccessToken::class,
            'table' => 'access_tokens',
            'primaryKey' => 'id',
            'columnsAsAttributesMap' => [
                'id' => 'id',
                'token' => 'token',
                'user_id' => 'userId',
                'created_dt' => 'createdDt',
            ],
            'criteriaMap' => [
                'id' => 'id_equalTo',
                'token' => 'token_equalTo',
            ]
        ],
        'User' => [
            'entityClass' => App\Domain\User\User::class,
            'table' => 'users',
            'primaryKey' => 'id',
            'columnsAsAttributesMap' => [
                'id' => 'id',
                'name' => 'name',
                'registered_dt' => 'registeredDt',
            ],
            'criteriaMap' => [
                'id' => 'id_equalTo',
            ]
        ],
    ],
];
