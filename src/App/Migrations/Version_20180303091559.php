<?php

namespace App\Migrations;

class Version_20180303091559 extends AbstractVersion
{
    public $description = 'create games table';

    public function up()
    {
        $this->executeQuery(
            'CREATE TABLE IF NOT EXISTS `games` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `status` TINYINT NOT NULL,
            `owner_id` INT(11) NOT NULL,
            `created_dt` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;'
        );
    }
}
