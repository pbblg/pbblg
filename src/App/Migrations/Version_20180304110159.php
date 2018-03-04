<?php

namespace App\Migrations;


class Version_20180304110159 extends AbstractVersion
{
    public $description = 'update access_tokens table';

    public function up()
    {
        $this->executeQuery('DROP TABLE `access_tokens`;');

        $this->executeQuery(
            'CREATE TABLE IF NOT EXISTS `access_tokens` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `token` CHAR(10) NOT NULL,
            `user_id` INT(11) NOT NULL,
            `created_dt` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;'
        );

        $this->executeQuery("ALTER TABLE `access_tokens` ADD UNIQUE KEY (`token`);");
    }
}