<?php

namespace AppTest\WebSocket\Command;

use App\WebSocket\Command\JoinGameCommandContext;
use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use TestUtils\TestCase;
use App\WebSocket\Action\JoinGame\JoinGameHandler;
use App\WebSocket\Action\Exception\NotAuthorizedException;
use App\WebSocket\Client;
use App\WebSocket\Event\JoinedGame;
use App\WebSocket\Action\Exception\GameNotExistsException;
use App\WebSocket\Action\Exception\GameNotOpenException;
use App\WebSocket\Command\JoinGameCommand;
use App\Domain\Game\Game;
use App\Domain\Game\GameStatus;
use App\Domain\Game\UsersInGames;

class JoinGameCommandTest extends TestCase
{
    public function testJoinToBadGame()
    {
        $gameRepository = $this->getRepository('Game');
        $usersRepository = $this->getRepository('User');

        $webSocketClient = $this->getWebSocketClient();

        $handler = new JoinGameCommand($gameRepository, $usersRepository, $webSocketClient);

        $request = $this->authorizeUser();

        $context = new JoinGameCommandContext($request->getAttribute('currentUser'), 222);

        $this->expectException(GameNotExistsException::class);
        $handler->handle($context);
    }

    public function testJoinToNotOpenGame()
    {
        $gameRepository = $this->getRepository('Game');
        $usersInGamesRepository = $this->getRepository('UsersInGames');

        $webSocketClient = $this->getWebSocketClient();

        $handler = new JoinGameCommand($gameRepository, $usersInGamesRepository, $webSocketClient);

        $game = new Game([
            'status' => GameStatus::STATUS_IN_PROGRESS,
            'ownerId' => 1,
        ]);
        $gameRepository->add($game);

        $request = $this->authorizeUser();

        $context = new JoinGameCommandContext($request->getAttribute('currentUser'), $game->getId());

        $this->expectException(GameNotOpenException::class);
        $handler->handle($context);
    }

    public function testJoinToGame()
    {
        $gameRepository = $this->getRepository('Game');
        $usersInGamesRepository = $this->getRepository('UsersInGames');

        $webSocketClient = $this->getWebSocketClient();

        $handler = new JoinGameCommand($gameRepository, $usersInGamesRepository, $webSocketClient);

        $game = $this->createOpenGame($gameRepository);

        $this->addOtherUserToGame($usersInGamesRepository, $game);

        $request = $this->authorizeUser();

        $context = new JoinGameCommandContext($request->getAttribute('currentUser'), $game->getId());

        $result = $handler->handle($context);

        $this->assertNull($result);
        $this->assertCount(2, $usersInGamesRepository->findMany([]), "2 users in game");

        // Мы добавили 2х пользователей в игруб проверим, что их там 2
        /** @var UsersInGames $userInGame */
        $userInGame = $usersInGamesRepository->findById(2);
        $this->assertInstanceOf(
            UsersInGames::class,
            $userInGame,
            "userInGame instance of UsersInGames"
        );
        $this->assertEquals(
            $this->getAuthorizedUser()->getId(),
            $userInGame->getUserId(),
            "Authorized user joined in game"
        );
        $this->assertEquals(
            $game->getId(),
            $userInGame->getGameId(),
            "Authorized user joined in requested game"
        );

        $this->assertCount(
            2,
            $webSocketClient->receivers,
            "2 users will receive a message"
        );
        $this->assertEquals(
            222,
            $webSocketClient->receivers[0],
            "User #222 receive a message"
        );
        $this->assertEquals(
            $this->getAuthorizedUser()->getId(),
            $webSocketClient->receivers[1],
            "Authorized user receive a message"
        );

        $event = $webSocketClient->event;
        $this->assertInstanceOf(JoinedGame::class, $event, "Event must be JoinedGame");
        $this->assertEquals(
            [
                'user' => [
                    'id' => $this->getAuthorizedUser()->getId(),
                    'name' => $this->getAuthorizedUser()->getName()
                ],
                'game' => [
                    'id' => $game->getId(),
                    'status' => $game->getStatus(),
                    'ownerId' => $game->getOwnerId(),
                    'created' => $game->getCreatedDt(),
                ],
            ],
            $event->getParams(),
            "Event has gameId param"
        );
    }

    private function createOpenGame($gameRepository)
    {
        $game = new Game([
            'status' => GameStatus::STATUS_OPEN,
            'ownerId' => 1,
            'createdDt' => date('Y-m-d H:i:s'),
        ]);
        $gameRepository->add($game);

        return $game;
    }

    private function addOtherUserToGame($usersInGamesRepository, $game)
    {
        $usersInGamesRepository->add(new UsersInGames([
            'userId' => 222,
            'gameId' => $game->getId()
        ]));
    }
}
