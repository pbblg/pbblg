<?php

namespace App\Migrations;

use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;

abstract class AbstractVersion
{
    /**
     * @var string
     */
    public $description = '';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Adapter
     */
    protected $dbAdapter;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    abstract public function up();

    protected function executeQuery($sql)
    {
        return $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
    }
}