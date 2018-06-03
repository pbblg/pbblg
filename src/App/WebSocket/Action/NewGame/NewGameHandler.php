<?php

namespace App\WebSocket\Action\NewGame;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Client;
use App\Domain\User\User;
use App\Domain\Game\Game;
use App\Domain\Game\ViewModel\Game as GameViewModel;
use App\WebSocket\Event\NewGameCreated;
use App\WebSocket\Action\Exception\NotAuthorizedException;
use App\WebSocket\Command\JoinGameCommand;
use App\WebSocket\Command\JoinGameCommandContext;
use App\Domain\Game\GameStatus;

class NewGameHandler implements ActionHandlerInterface
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
     * @var JoinGameCommand
     */
    private $joinGameCommand;

    public function __construct(
        RepositoryInterface $gameRepository,
        RepositoryInterface $usersInGameRepository,
        Client $webSocketClient,
        JoinGameCommand $joinGameCommand
    ) {
        $this->gameRepository = $gameRepository;
        $this->usersInGameRepository = $usersInGameRepository;
        $this->webSocketClient = $webSocketClient;
        $this->joinGameCommand = $joinGameCommand;
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
            'status' => GameStatus::STATUS_OPEN,
            'ownerId' => $user->getId(),
            'createdDt' => date('Y-m-d H:i:s'),
        ]);

        $this->gameRepository->add($game);

        $gameViewModel = new GameViewModel($game);
        $this->webSocketClient->send([], new NewGameCreated($gameViewModel));

        $this->joinGameCommand->handle(new JoinGameCommandContext($user, $game->getId()));

        return $gameViewModel->extract();
    }
}
