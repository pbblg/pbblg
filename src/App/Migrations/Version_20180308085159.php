<?php

namespace App\Migrations;


class Version_20180308085159 extends AbstractVersion
{
    public $description = 'create users_in_games table';

    public function up()
    {
        $this->executeQuery(
            'CREATE TABLE `users_in_games` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `user_id` INT,
                `game_id` INT,
                 PRIMARY KEY (`id`)
            );'
        );

        $this->executeQuery("ALTER TABLE `users_in_games`
              ADD UNIQUE KEY `users_in_game_idx` (`user_id`, `game_id`);");
    }
}