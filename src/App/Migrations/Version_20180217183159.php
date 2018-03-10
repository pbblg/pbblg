<?php

namespace App\Migrations;

class Version_20180217183159 extends AbstractVersion
{
    public $description = 'create access_tokens table';

    public function up()
    {
        $this->executeQuery(
            'CREATE TABLE IF NOT EXISTS `access_tokens` (
            `id` CHAR(10) NOT NULL,
            `user_id` INT(11) NOT NULL,
            `created_dt` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;'
        );
    }
}
