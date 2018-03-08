<?php

namespace App\WebSocket\Action\NewGame;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Client;
use App\Domain\User\User;
use App\Domain\Game\Game;
use App\WebSocket\Event\NewGameCreated;
use App\WebSocket\Action\Exception\NotAuthorizedException;

class NewGameHandler implements ActionHandlerInterface
{
    /**
     * @var RepositoryInterface
     */
    private $gameRepository;

    /**
     * @var Client
     */
    private $webSocketClient;

    public function __construct(
        RepositoryInterface $gameRepository,
        Client $webSocketClient
    ) {
        $this->gameRepository = $gameRepository;
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

        /** @var User $user */
        $user = $request->getAttribute('currentUser');

        $game = new Game([
            'status' => Game::STATUS_OPEN,
            'ownerId' => $user->getId(),
        ]);

        $this->gameRepository->add($game);

        $this->webSocketClient->send([], new NewGameCreated($game->getId()));

        return [
            'gameId' => $game->getId(),
        ];
    }
}
