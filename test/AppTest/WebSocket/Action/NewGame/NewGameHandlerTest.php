<?php

namespace AppTest\WebSocket\Action\Ping;

use App\Domain\Game\Game;
use App\WebSocket\Event\NewGameCreated;
use TestUtils\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\NewGame\NewGameHandler;
use App\WebSocket\Client;
use App\WebSocket\Action\Exception\NotAuthorizedException;

class NewGameHandlerTest extends TestCase
{
    public function testThrowException()
    {
        $gameRepository = $this->prophesize(RepositoryInterface::class)->reveal();
        $webSocketClient = $this->prophesize(Client::class)->reveal();

        $handler = new NewGameHandler($gameRepository, $webSocketClient);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute('currentUser')
            ->willReturn(null);

        $this->expectException(NotAuthorizedException::class);

        $handler->handle($request->reveal());
    }

    public function testGameCreating()
    {

        $gameRepository = $this->getRepository('Game');

        $webSocketClient = $this->getWebSocketClient();

        $handler = new NewGameHandler($gameRepository, $webSocketClient);

        $request = $this->authorizeUser();

        $handler->handle($request);

        $this->assertCount(1, $gameRepository->findMany([]), "Created only 1 game");
        /** @var Game $game */
        $game = $gameRepository->findById(1);
        $this->assertInstanceOf(Game::class, $game, "Game instance of Game");
        $this->assertEquals(Game::STATUS_OPEN, $game->getStatus(), "Created game have status OPEN");
        $this->assertEquals($this->getAuthorizedUser()->getId(), $game->getOwnerId(), "Created game has authorized user as owner");

        $this->assertCount(0, $webSocketClient->receivers, "Everyone will receive a message");
        $event = $webSocketClient->event;
        $this->assertInstanceOf(NewGameCreated::class, $event, "Event must be NewGameCreated");
        $this->assertEquals(['gameId' => $game->getId()], $event->getParams(), "Event has gameId param");
    }
}
