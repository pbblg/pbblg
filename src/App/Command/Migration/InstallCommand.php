<?php

namespace App\Command\Migration;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class InstallCommand
{
    /**
     * @var Adapter
     */
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function handle()
    {
        /** @var ResultSet $result */
        $result = $this->dbAdapter->query("SHOW TABLES LIKE 'migrations'", Adapter::QUERY_MODE_EXECUTE);

        if ($result->count()) {
            return false;
        }

        $this->dbAdapter->query(
            'CREATE TABLE IF NOT EXISTS `migrations` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `version` BIGINT(1) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;',
            Adapter::QUERY_MODE_EXECUTE
        );

        $this->dbAdapter->query(
            "ALTER TABLE `migrations`
              ADD UNIQUE KEY `version_idx` (`version`);",
            Adapter::QUERY_MODE_EXECUTE
        );

        return true;
    }
}