<?php

namespace AppTest\WebSocket\Action\JoinGame;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use TestUtils\TestCase;
use App\WebSocket\Action\JoinGame\JoinGameHandler;
use App\WebSocket\Action\Exception\NotAuthorizedException;
use App\WebSocket\Client;
use App\WebSocket\Event\JoinedGame;
use App\WebSocket\Action\Exception\GameNotExistsException;
use App\WebSocket\Action\Exception\GameNotOpenException;
use App\Domain\Game\Game;
use App\Domain\Game\GameStatus;
use App\Domain\Game\UsersInGames;

class JoinGameHandlerTest extends TestCase
{
    public function testThrowException()
    {
        $gameRepository = $this->prophesize(RepositoryInterface::class)->reveal();
        $usersRepository = $this->prophesize(RepositoryInterface::class)->reveal();
        $webSocketClient = $this->prophesize(Client::class)->reveal();

        $handler = new JoinGameHandler($gameRepository, $usersRepository, $webSocketClient);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute('currentUser')
            ->willReturn(null);

        $this->expectException(NotAuthorizedException::class);

        $handler->handle($request->reveal());
    }

    public function testJoinToBadGame()
    {
        $gameRepository = $this->getRepository('Game');
        $usersRepository = $this->getRepository('User');

        $webSocketClient = $this->getWebSocketClient();

        $handler = new JoinGameHandler($gameRepository, $usersRepository, $webSocketClient);

        $request = $this->authorizeUser();
        $request = $request->withQueryParams(['gameId' => 222]);

        $this->expectException(GameNotExistsException::class);
        $handler->handle($request);
    }

    public function testJoinToNotOpenGame()
    {
        $gameRepository = $this->getRepository('Game');
        $usersInGamesRepository = $this->getRepository('UsersInGames');

        $webSocketClient = $this->getWebSocketClient();

        $handler = new JoinGameHandler($gameRepository, $usersInGamesRepository, $webSocketClient);

        $game = new Game([
            'status' => GameStatus::STATUS_IN_PROGRESS,
            'ownerId' => 1,
        ]);
        $gameRepository->add($game);

        $request = $this->authorizeUser();
        $request = $request->withQueryParams(['gameId' => $game->getId()]);

        $this->expectException(GameNotOpenException::class);
        $handler->handle($request);
    }

    public function testJoinToGame()
    {
        $gameRepository = $this->getRepository('Game');
        $usersInGamesRepository = $this->getRepository('UsersInGames');

        $webSocketClient = $this->getWebSocketClient();

        $handler = new JoinGameHandler($gameRepository, $usersInGamesRepository, $webSocketClient);

        $game = $this->createOpenGame($gameRepository);

        $this->addOtherUserToGame($usersInGamesRepository, $game);

        $request = $this->authorizeUser();
        $request = $request->withQueryParams(['gameId' => $game->getId()]);

        $result = $handler->handle($request);

        $this->assertEquals('ok', $result);
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
