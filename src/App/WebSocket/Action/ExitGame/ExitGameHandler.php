<?php

namespace App\WebSocket\Action\ExitGame;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Client;
use App\Domain\User\User;
use App\Domain\Game\Game;
use App\Domain\Game\ViewModel\Game as GameViewModel;
use App\Domain\Game\UsersInGames;
use App\WebSocket\Action\Exception\NotAuthorizedException;
use App\WebSocket\Event\PlayerLeftTheGame;
use App\WebSocket\Event\GameRemoved;

class ExitGameHandler implements ActionHandlerInterface
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

        /** @var UsersInGames $userInGame */
        $userInGame = $this->usersInGameRepository->find(['userId' => $user->getId()]);

        if (!$userInGame) {
            return;
        }

        $this->usersInGameRepository->remove($userInGame);

        /** @var UsersInGames[] $usersInGame */
        $usersInGame = $this->usersInGameRepository->findMany(['gameId' => $userInGame->getGameId()]);

        $members = [];
        /** @var UsersInGames $userInGame */
        foreach ($usersInGame as $userInGameItem) {
            $members[] = $userInGameItem->getUserId();
        }

        if ($members) {
            $this->webSocketClient->send($members, new PlayerLeftTheGame([
                'id' => $user->getId(),
                'name' => $user->getName()
            ]));
        } else {
            // no players in game
            /** @var Game $game */
            $game = $this->gameRepository->find(['id' => $userInGame->getGameId()]);

            if ($game) {
                $this->gameRepository->remove($game);

                $this->webSocketClient->send([], new GameRemoved(new GameViewModel($game)));
            }
        }

        return;
    }
}
