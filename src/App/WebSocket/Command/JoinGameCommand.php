<?php

namespace App\WebSocket\Command;

use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Client;
use App\Domain\Game\Game;
use App\Domain\User\User;
use App\Domain\Game\UsersInGames;
use App\Domain\Game\GameStatus;
use App\Domain\Game\ViewModel\Game as GameViewModel;
use App\WebSocket\Action\Exception\GameNotExistsException;
use App\WebSocket\Action\Exception\GameNotOpenException;
use App\WebSocket\Event\JoinedGame;

class JoinGameCommand
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
     * @param JoinGameCommandContext $context
     * @return void
     */
    public function handle(JoinGameCommandContext $context)
    {
        /** @var User $user */
        $user = $context->getUser();

        /** @var Game $game */
        $game = $this->gameRepository->find(['id' => $context->getGameId()]);

        if (!$game) {
            throw new GameNotExistsException($context->getGameId());
        }

        if ($game->getStatus() != GameStatus::STATUS_OPEN) {
            throw new GameNotOpenException($game->getId());
        }

        $userInGame = $this->usersInGameRepository->find([
            'userId' => $user->getId(),
            'gameId' => $game->getId()
        ]);

        if ($userInGame) {
            return;
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

        $this->webSocketClient->send(
            $members,
            new JoinedGame(
                [
                    'id' => $user->getId(),
                    'name' => $user->getName()
                ],
                new GameViewModel($game)
            )
        );
    }
}
