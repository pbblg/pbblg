<?php

namespace AppTest\WebSocket\Action\Ping;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\NewGame\NewGameHandler;
use App\WebSocket\Client;
use App\WebSocket\Action\Exception\NotAuthorizedException;

class NewGameHandlerTest extends TestCase
{
    public function testReturnsHtmlResponseWhenTemplateRendererProvided()
    {
        $gameRepository = $this->prophesize(RepositoryInterface::class)->reveal();
        $webSocketClient = $this->prophesize(Client::class)->reveal();

        $handler = new NewGameHandler($gameRepository, $webSocketClient);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute('currentUser')
            ->willReturn(null);

        $this->expectException(NotAuthorizedException::class);

        $response = $handler->handle(
            $request->reveal()
        );

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('gameId', $response);
        $this->assertEquals(1, $response['gameId']);
    }
}
