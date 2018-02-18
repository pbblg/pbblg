<?php

namespace App\Domain\AccessToken;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

class Repository
{
    /**
     * @var TableGateway
     */
    private $table;

    public function __construct(TableGateway $table)
    {
        $this->table = $table;
    }

    /**
     * @param AccessToken $accessToken
     * @return AccessToken
     */
    public function add(AccessToken $accessToken)
    {
        $this->table->insert([
            'id' => $accessToken->getId(),
            'user_id' => $accessToken->getUserId(),
        ]);

        return $accessToken;
    }

    /**
     * @param string $id
     * @return AccessToken|null
     */
    public function fetch($id)
    {
        /** @var ResultSet $result */
        $result = $this->table->select(['id' => $id]);

        if ($result->count() == 0) {
            return null;
        }

        $data = $result->current();

        return new AccessToken(
            $data['id'],
            $data['user_id']
        );
    }
}