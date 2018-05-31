<?php

namespace AppTest\WebSocket\Action\JoinGame;

use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Client;
use App\WebSocket\Action\StartGame\StartGameHandler;
use App\WebSocket\Action\Exception\NotAuthorizedException;
use App\WebSocket\Action\Exception\GameNotExistsException;
use App\WebSocket\Action\Exception\GameNotOpenException;
use App\WebSocket\Action\Exception\TooFewPlayersException;
use App\WebSocket\Event\UserGotCard;
use App\WebSocket\Event\TakeCard;
use App\Domain\User\User;
use App\Domain\Game\Game;
use App\Domain\Game\GameStatus;
use App\Domain\Game\UsersInGames;
use TestUtils\TestCase;
use TestUtils\WebSocketClientStub;

class StartGameHandlerTest extends TestCase
{
    public function testThrowException()
    {
        $gameRepository = $this->prophesize(RepositoryInterface::class)->reveal();
        $usersInGamesRepository = $this->prophesize(RepositoryInterface::class)->reveal();
        $usersRepository = $this->prophesize(RepositoryInterface::class)->reveal();
        $webSocketClient = $this->prophesize(Client::class)->reveal();

        $handler = new StartGameHandler($gameRepository, $usersInGamesRepository, $usersRepository, $webSocketClient);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute('currentUser')
            ->willReturn(null);

        $this->expectException(NotAuthorizedException::class);

        $handler->handle($request->reveal());
    }

    public function testStartBadGame()
    {
        $gameRepository = $this->getRepository('Game');
        $usersInGamesRepository = $this->getRepository('UsersInGames');
        $usersRepository = $this->getRepository('User');
        $webSocketClient = $this->getWebSocketClient();

        $handler = new StartGameHandler($gameRepository, $usersInGamesRepository, $usersRepository, $webSocketClient);

        $request = $this->authorizeUser();
        $request = $request->withQueryParams(['gameId' => 222]);

        $this->expectException(GameNotExistsException::class);
        $handler->handle($request);
    }

    public function testStartNotOpenGame()
    {
        $gameRepository = $this->getRepository('Game');
        $usersInGamesRepository = $this->getRepository('UsersInGames');
        $userRepository = $this->getRepository('User');
        $webSocketClient = $this->getWebSocketClient();

        $handler = new StartGameHandler($gameRepository, $usersInGamesRepository, $userRepository, $webSocketClient);

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

    public function testStartGameWithTooFewUsers()
    {
        $gameRepository = $this->getRepository('Game');
        $usersInGamesRepository = $this->getRepository('UsersInGames');
        $userRepository = $this->getRepository('User');
        $webSocketClient = $this->getWebSocketClient();

        $handler = new StartGameHandler($gameRepository, $usersInGamesRepository, $userRepository, $webSocketClient);

        $game = new Game([
            'status' => GameStatus::STATUS_OPEN,
            'ownerId' => 1,
        ]);
        $gameRepository->add($game);

        $request = $this->authorizeUser();
        $request = $request->withQueryParams(['gameId' => $game->getId()]);

        $this->expectException(TooFewPlayersException::class);
        $handler->handle($request);
    }

    public function testStartGame()
    {
        $gameRepository = $this->getRepository('Game');
        $usersInGamesRepository = $this->getRepository('UsersInGames');
        $userRepository = $this->getRepository('User');
        $webSocketClient = $this->getWebSocketClient();

        $handler = new StartGameHandler($gameRepository, $usersInGamesRepository, $userRepository, $webSocketClient);

        $game = $this->createOpenGame($gameRepository);

        $user1 = $userRepository->add(new User());
        $user2 = $userRepository->add(new User());
        $user3 = $userRepository->add(new User());

        $usersInGamesRepository->add(new UsersInGames(['userId' => $user1->getId(), 'gameId' => $game->getId()]));
        $usersInGamesRepository->add(new UsersInGames(['userId' => $user2->getId(), 'gameId' => $game->getId()]));
        $usersInGamesRepository->add(new UsersInGames(['userId' => $user3->getId(), 'gameId' => $game->getId()]));

        $request = $this->authorizeUser();
        $request = $request->withQueryParams(['gameId' => $game->getId()]);

        $result = $handler->handle($request);

        $this->assertEquals('ok', $result);
        $this->assertCount(3, $usersInGamesRepository->findMany([]), "3 users in game");

        // Мы добавили 3х пользователей в игру, проверим, что их там 3
        $this->assertUserInGame($usersInGamesRepository, $user1->getId(), $game->getId());
        $this->assertUserInGame($usersInGamesRepository, $user2->getId(), $game->getId());
        $this->assertUserInGame($usersInGamesRepository, $user3->getId(), $game->getId());

        // первое сообщение пользователю1 о его карте
        $this->assertWebSocketReceiver(
            $webSocketClient,
            0,
            [$user1->getId()]
        );

        $event = $webSocketClient->wasSend[0]['event'];
        $this->assertInstanceOf(TakeCard::class, $event, "Event must be " . TakeCard::class);
        $this->assertEquals($user1->extract(), $event->getParam('user'), "Event has params user1");
        $this->assertInternalType('int', $event->getParam('card'), "Event has params card");

        // второе сообщение всем о том, что пользователь1 получил карту
        $this->assertWebSocketReceiver(
            $webSocketClient,
            1,
            [$user2->getId(), $user3->getId()]
        );

        $event = $webSocketClient->wasSend[1]['event'];
        $this->assertInstanceOf(UserGotCard::class, $event, "Event must be " . UserGotCard::class);
        $this->assertEquals($user1->extract(), $event->getParam('user'), "Event has params user1");

        // третье сообщение пользотелю2 о его карте
        $this->assertWebSocketReceiver(
            $webSocketClient,
            2,
            [$user2->getId()]
        );

        $event = $webSocketClient->wasSend[2]['event'];
        $this->assertInstanceOf(TakeCard::class, $event, "Event must be " . TakeCard::class);
        $this->assertEquals($user2->extract(), $event->getParam('user'), "Event has params user1");
        $this->assertInternalType('int', $event->getParam('card'), "Event has params card");

        // четвертое сообщение всем о том, что пользователь2 получил карту
        $this->assertWebSocketReceiver(
            $webSocketClient,
            3,
            [$user1->getId(), $user3->getId()]
        );

        $event = $webSocketClient->wasSend[3]['event'];
        $this->assertInstanceOf(UserGotCard::class, $event, "Event must be " . UserGotCard::class);
        $this->assertEquals($user2->extract(), $event->getParam('user'), "Event has params user1");

        // пятое сообщение пользотелю3 о его карте
        $this->assertWebSocketReceiver(
            $webSocketClient,
            4,
            [$user3->getId()]
        );

        $event = $webSocketClient->wasSend[4]['event'];
        $this->assertInstanceOf(TakeCard::class, $event, "Event must be " . TakeCard::class);
        $this->assertEquals($user3->extract(), $event->getParam('user'), "Event has params user1");
        $this->assertInternalType('int', $event->getParam('card'), "Event has params card");

        // шестое сообщение всем о том, что пользователь3 получил карту
        $this->assertWebSocketReceiver(
            $webSocketClient,
            5,
            [$user1->getId(), $user2->getId()]
        );

        $event = $webSocketClient->wasSend[5]['event'];
        $this->assertInstanceOf(UserGotCard::class, $event, "Event must be " . UserGotCard::class);
        $this->assertEquals($user3->extract(), $event->getParam('user'), "Event has params user1");
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

    private function assertUserInGame($usersInGamesRepository, $userId, $gameId)
    {
        /** @var UsersInGames $userInGame */
        $userInGame = $usersInGamesRepository->find(['userId' => $userId, 'gameId' => $gameId]);
        $this->assertInstanceOf(
            UsersInGames::class,
            $userInGame,
            "userInGame instance of UsersInGames"
        );
        $this->assertEquals(
            $userId,
            $userInGame->getUserId(),
            "Authorized user joined in game"
        );
        $this->assertEquals(
            $gameId,
            $userInGame->getGameId(),
            "Authorized user joined in requested game"
        );
    }

    private function assertWebSocketReceiver(WebSocketClientStub $webSocketClient, $number, $receivers)
    {
        $send = $webSocketClient->wasSend[$number];

        $this->assertCount(
            count($receivers),
            $send['receivers'],
            count($receivers) . " users will receive a message"
        );

        foreach ($receivers as $key => $receiver) {
            $this->assertEquals(
                $receiver,
                $send['receivers'][$key],
                sprintf("User #%s receive a message", $receiver)
            );
        }
    }
}
