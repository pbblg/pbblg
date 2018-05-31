<?php

namespace App\WebSocket\Action\GetOnlineUsers;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Client;
use App\Domain\User\User;
use App\WebSocket\Action\Exception\NotAuthorizedException;

class GetOnlineUsersHandler implements ActionHandlerInterface
{
    /**
     * @var RepositoryInterface
     */
    private $usersRepository;

    /**
     * @var Client
     */
    private $webSocketClient;

    public function __construct(
        RepositoryInterface $usersRepository,
        Client $webSocketClient
    ) {
        $this->usersRepository = $usersRepository;
        $this->webSocketClient = $webSocketClient;
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed result
     */
    public function handle(ServerRequestInterface $request)
    {
        if (!$request->getAttribute('currentUser')) {
            throw new NotAuthorizedException();
        }

        $userIds = $this->webSocketClient->getOnlineUsers();

        /** @var User[] $users */
        $users = $this->usersRepository->findMany(['id_in' =>$userIds]);

        $result = [];

        foreach ($users as $user) {
            $result[$user->getId()] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
            ];
        }

        return $result;
    }
}
