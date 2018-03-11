<?php

namespace AppTest\WebSocket\Action\Ping;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use App\WebSocket\Action\Ping\PingHandler;

class PingHandlerTest extends TestCase
{
    public function testReturnsString()
    {
        $pingHandler = new PingHandler();

        $response = $pingHandler->handle(
            $this->prophesize(ServerRequestInterface::class)->reveal()
        );

        $this->assertEquals('pong', $response);
    }
}
