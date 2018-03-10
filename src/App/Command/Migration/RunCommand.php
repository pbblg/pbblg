<?php

namespace App\Command\Migration;

use RuntimeException;
use Psr\Container\ContainerInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use App\Migrations\AbstractVersion;

class RunCommand
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TableGateway
     */
    private $migrationsTable;

    public function __construct(
        ContainerInterface $container,
        TableGateway $migrationsTable
    ) {

        $this->container = $container;
        $this->migrationsTable = $migrationsTable;
    }

    /**
     * @param RunCommandContext $context
     * @return void
     */
    public function handle(RunCommandContext $context)
    {
        $versionClass = 'App\Migrations\Version_' . $context->getVersionNum();

        if (!class_exists($versionClass)) {
            throw new RuntimeException("Version $versionClass not exists");
        }

        $version = new $versionClass($this->container);

        if (!$version instanceof AbstractVersion) {
            throw new RuntimeException("Version $versionClass not instance of " . AbstractVersion::class);
        }

        if ($this->isRegistered($context->getVersionNum())) {
            throw new RuntimeException("Version " . $context->getVersionNum() ." already applied");
        }

        $version->up();

        $this->register($context->getVersionNum());
    }

    private function register($versionNum)
    {
        $this->migrationsTable->insert(['version' => $versionNum]);
    }

    private function isRegistered($versionNum)
    {
        /** @var ResultSet $result */
        $result = $this->migrationsTable->select(['version' => $versionNum]);
        return $result->count();
    }
}
