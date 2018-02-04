<?php

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => "mysql:dbname=pbblg;host=localhost",
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ],
        'username' => 'pbblg',
        'password' => '111',
    ]
];