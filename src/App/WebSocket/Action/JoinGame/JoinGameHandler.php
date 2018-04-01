<?php

namespace App\WebSocket\Action\JoinGame;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Client;
use App\Domain\Game\Game;
use App\Domain\User\User;
use App\Domain\Game\UsersInGames;
use App\Domain\Game\GameStatus;
use App\WebSocket\Action\Exception\GameNotExistsException;
use App\WebSocket\Action\Exception\GameNotOpenException;
use App\WebSocket\Action\Exception\NotAuthorizedException;
use App\WebSocket\Event\JoinedGame;

class JoinGameHandler implements ActionHandlerInterface
{
    /**
     * @var RepositoryInterface
     */
    private $gameRepository;

    /**
     * @var RepositoryInterface
     */
    private $usersInGameRepository;

    /**
     * @var Client
     */
    private $webSocketClient;

    /**
     * JoinGameHandler constructor.
     * @param RepositoryInterface $gameRepository
     * @param RepositoryInterface $usersInGameRepository
     * @param Client $webSocketClient
     */
    public function __construct(
        RepositoryInterface $gameRepository,
        RepositoryInterface $usersInGameRepository,
        Client $webSocketClient
    ) {
        $this->gameRepository = $gameRepository;
        $this->usersInGameRepository = $usersInGameRepository;
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

        $params = $request->getQueryParams();

        /** @var Game $game */
        $game = $this->gameRepository->find(['id' => $params['gameId']]);

        if (!$game) {
            throw new GameNotExistsException($params['gameId']);
        }

        if ($game->getStatus() != GameStatus::STATUS_OPEN) {
            throw new GameNotOpenException($game->getId());
        }

        $this->usersInGameRepository->add(new UsersInGames([
            'userId' => $user->getId(),
            'gameId' => $game->getId()
        ]));

        $usersInGame = $this->usersInGameRepository->findMany(['gameId' => $game->getId()]);

        $members = [];
        /** @var UsersInGames $userInGame */
        foreach ($usersInGame as $userInGame) {
            $members[] = $userInGame->getUserId();
        }

        $this->webSocketClient->send($members, new JoinedGame([
            'id' => $user->getId(),
            'name' => $user->getName()
        ]));

        return 'ok';
    }
}
