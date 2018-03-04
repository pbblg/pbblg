<?php

namespace App\Migrations;


class Version_20180304102359 extends AbstractVersion
{
    public $description = 'add registered dt to users';

    public function up()
    {
        $this->executeQuery(
            'ALTER TABLE `users` ADD `registered_dt` DATETIME NOT NULL DEFAULT NOW() AFTER `password`'
        );
    }
}