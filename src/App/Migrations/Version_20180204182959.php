<?php

namespace App\Migrations;

class Version_20180204182959 extends AbstractVersion
{
    public $description = 'create users table';

    public function up()
    {
        $this->executeQuery(
            'CREATE TABLE IF NOT EXISTS `users` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NOT NULL,
            `password` VARCHAR(60) NOT NULL,
            `is_admin` TINYINT NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;'
        );
    }
}
