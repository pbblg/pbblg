<?php

namespace App\Domain\User;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

class Repository
{
    /**
     * @var TableGateway
     */
    private $usersTable;

    public function __construct(TableGateway $usersTable)
    {
        $this->usersTable = $usersTable;
    }

    /**
     * @param string $name
     * @return User
     */
    public function fetchByName($name)
    {
        /** @var ResultSet $result */
        $result = $this->usersTable->select(['name' => $name]);
        $data = $result->current();

        return new User(
            $data['id'],
            $data['name']
        );
    }
}