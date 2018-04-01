<?php

namespace App\WebSocket\Action\StartGame;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Client;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Action\Exception\GameNotExistsException;
use App\WebSocket\Action\Exception\GameNotOpenException;
use App\WebSocket\Action\Exception\TooFewPlayersException;
use App\WebSocket\Action\Exception\NotAuthorizedException;
use App\WebSocket\Event\TakeCard;
use App\WebSocket\Event\UserGotCard;
use App\Domain\Game\Deck;
use App\Domain\Game\GameStatus;
use App\Domain\Game\Game;
use App\Domain\Game\UsersInGames;
use App\Domain\User\User;
use App\Domain\Collection;

class StartGameHandler implements ActionHandlerInterface
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
     * @var RepositoryInterface
     */
    private $usersRepository;

    /**
     * @var Client
     */
    private $webSocketClient;

    /**
     * JoinGameHandler constructor.
     * @param RepositoryInterface $gameRepository
     * @param RepositoryInterface $usersInGameRepository
     * @param RepositoryInterface $usersRepository
     * @param Client $webSocketClient
     */
    public function __construct(
        RepositoryInterface $gameRepository,
        RepositoryInterface $usersInGameRepository,
        RepositoryInterface $usersRepository,
        Client $webSocketClient
    ) {
        $this->gameRepository = $gameRepository;
        $this->usersInGameRepository = $usersInGameRepository;
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

        $params = $request->getQueryParams();

        /** @var Game $game */
        $game = $this->fetchGame($params['gameId']);
        /** @var Collection $users */
        $users = $this->fetchPlayers($game->getId());
        $deck = Deck::generate();

        foreach ($users as $user) {
            $card = $deck->shift();
            $this->webSocketClient->send([$user->getId()], new TakeCard($user->extract(), $card));

            $receivers = $users->getExclude($user->getId());
            $this->webSocketClient->send($receivers->getIds(), new UserGotCard($user->extract()));
        }

        return 'ok';
    }

    /**
     * @param int $gameId
     * @return Game
     */
    private function fetchGame($gameId)
    {
        /** @var Game $game */
        $game = $this->gameRepository->find(['id' => $gameId]);

        if (!$game) {
            throw new GameNotExistsException($gameId);
        }

        if ($game->getStatus() != GameStatus::STATUS_OPEN) {
            throw new GameNotOpenException($game->getId());
        }

        return $game;
    }

    /**
     * @param $gameId
     * @return User[]
     */
    private function fetchPlayers($gameId)
    {
        /** @var UsersInGames[] $usersInGame */
        $usersInGame = $this->usersInGameRepository->findMany(['gameId' => $gameId]);

        if (count($usersInGame) < 3) {
            throw new TooFewPlayersException($gameId);
        }

        $userIds = [];
        foreach ($usersInGame as $userInGame) {
            $userIds[] = $userInGame->getUserId();
        }

        return $this->usersRepository->findMany(['id_in' => $userIds]);
    }
}
