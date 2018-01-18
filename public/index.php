<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$server = new Game\Application\Http\Server();

$server->listen();