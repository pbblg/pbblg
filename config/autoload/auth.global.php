<?php

return [
    'authentication' => [
        'pdo' => [
            'dsn' => 'mysql:dbname=pbblg;host=localhost',
            'username' => 'pbblg',
            'password' => '111',
            'table' => 'users',
            'field' => [
                'identity' => 'name',
                'password' => 'password',
            ],
        ],
        'username' => 'username',
        'password' => 'password',
    ],
];