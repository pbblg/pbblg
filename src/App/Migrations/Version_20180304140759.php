<?php

namespace App\Migrations;

class Version_20180304140759 extends AbstractVersion
{
    public $description = 'create sessions table';

    public function up()
    {
        $this->executeQuery(
            'CREATE TABLE `sessions` (
                `id` char(32),
                `name` char(32),
                `modified` int,
                `lifetime` int,
                `data` text,
                 PRIMARY KEY (`id`, `name`)
            );'
        );
    }
}
