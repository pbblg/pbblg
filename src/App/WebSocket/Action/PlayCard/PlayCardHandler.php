<?php

namespace App\WebSocket\Action\PlayCard;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Client;
use App\WebSocket\Action\ActionHandlerInterface;
use App\WebSocket\Action\Exception\GameNotExistsException;
use App\WebSocket\Action\Exception\GameNotOpenException;
use App\WebSocket\Action\Exception\TooFewPlayersException;
use App\WebSocket\Action\Exception\BadCardException;
use App\WebSocket\Action\Exception\UserNotInGameException;
use App\WebSocket\Action\Exception\NotAuthorizedException;
use App\WebSocket\Event\TakeCard;
use App\WebSocket\Event\UserGotCard;
use App\Domain\Game\Deck;
use App\Domain\Game\GameStatus;
use App\Domain\Game\Game;
use App\Domain\Game\UsersInGames;
use App\Domain\Game\PlayCardService;
use App\Domain\User\User;
use App\Domain\Collection;

class PlayCardHandler implements ActionHandlerInterface
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
     * @var PlayCardService
     */
    private $playCardService;

    /**
     * @var Client
     */
    private $webSocketClient;

    /**
     * JoinGameHandler constructor.
     * @param RepositoryInterface $gameRepository
     * @param RepositoryInterface $usersInGameRepository
     * @param RepositoryInterface $usersRepository
     * @param PlayCardService $playCardService
     * @param Client $webSocketClient
     */
    public function __construct(
        RepositoryInterface $gameRepository,
        RepositoryInterface $usersInGameRepository,
        RepositoryInterface $usersRepository,
        PlayCardService $playCardService,
        Client $webSocketClient
    ) {
        $this->gameRepository = $gameRepository;
        $this->usersInGameRepository = $usersInGameRepository;
        $this->usersRepository = $usersRepository;
        $this->playCardService = $playCardService;
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

        /** @var User $currentUser */
        $currentUser = $request->getAttribute('currentUser');

        // Проверить, что игра все еще открыта
        /** @var Game $game */
        $game = $this->fetchGame($params['gameId']);


        // Проверить, что такая карта есть у этого пользователя
        if (!$this->userHasCard($currentUser->getId(), $game->getId(), $params['cardId'])) {
            throw new BadCardException($params['cardId']);
        }


        // Проверить, что юзер, на которого играется карта - есть за этим столом
        /** @var Collection $users */
        $users = $this->fetchPlayers($game->getId());
        if (isset($users[$params['targetUserId']])) {
            /** @var User $targetUser */
            $targetUser = $users[$params['targetUserId']];
        } else {
            throw new UserNotInGameException($params['targetUserId'], $game->getId());
        }

        // Сыграть карту
        $this->playCardService->play($currentUser, $params['cardId'], $targetUser, $params['targetCardId']);


        // Выдать карту следующему пользователю
        $deck = Deck::generate();
        $card = $deck->shift();
        $this->webSocketClient->send([$currentUser->getId()], new TakeCard($currentUser->extract(), $card));

        $receivers = $users->getExclude($currentUser->getId());
        $this->webSocketClient->send($receivers->getIds(), new UserGotCard($currentUser->extract()));


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

    private function userHasCard($userId, $gameId, $cardId)
    {
        return true;
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
