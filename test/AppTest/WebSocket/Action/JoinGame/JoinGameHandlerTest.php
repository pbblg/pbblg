<?php

namespace AppTest\WebSocket\Action\JoinGame;

use App\WebSocket\Command\JoinGameCommand;
use Psr\Http\Message\ServerRequestInterface;
use TestUtils\TestCase;
use App\WebSocket\Action\JoinGame\JoinGameHandler;
use App\WebSocket\Action\Exception\NotAuthorizedException;

class JoinGameHandlerTest extends TestCase
{
    public function testThrowException()
    {
        $command = $this->prophesize(JoinGameCommand::class)->reveal();

        $handler = new JoinGameHandler($command);

        $request = $this->prophesize(ServerRequestInterface::class);
        $request
            ->getAttribute('currentUser')
            ->willReturn(null);

        $this->expectException(NotAuthorizedException::class);

        $handler->handle($request->reveal());
    }
}
