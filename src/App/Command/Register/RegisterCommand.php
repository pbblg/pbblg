<?php

namespace App\Command\Register;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

class RegisterCommand
{
    /**
     * @var TableGateway
     */
    private $userTable;

    public function __construct(TableGateway $userTable)
    {
        $this->userTable = $userTable;
    }

    /**
     * @param RegisterCommandContext $context
     * @return array
     */
    public function handle(RegisterCommandContext $context)
    {
        if ($this->userExists($context)) {
            throw new UserAlreadyExistsException($context->getUserName());
        }

        $this->userTable->insert([
            'name' => $context->getUserName(),
            'password' => password_hash($context->getPassword(), PASSWORD_BCRYPT),
        ]);

        $result = $this->userTable->select(['name' => $context->getUserName()]);

        if (!$result->count()) {
            throw new UserCreationException();
        }

        return [
            'id' => $result->current()->id,
            'username' => $result->current()->name,
            'password' => $result->current()->password,
            'is_admin' => $result->current()->is_admin,
        ];
    }

    private function userExists(RegisterCommandContext $context)
    {
        /** @var ResultSet $result */
        $result = $this->userTable->select(['name' => $context->getUserName()]);

        if ($result->count()) {
            return true;
        }
    }
}
