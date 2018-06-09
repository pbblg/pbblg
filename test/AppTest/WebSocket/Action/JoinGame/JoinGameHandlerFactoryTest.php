<?php

namespace AppTest\WebSocket\Action\JoinGame;

use App\WebSocket\Command\JoinGameCommand;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use App\WebSocket\Action\JoinGame\JoinGameHandlerFactory;
use App\WebSocket\Action\JoinGame\JoinGameHandler;

class JoinGameHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $command = $this->prophesize(JoinGameCommand::class);

        $this->container->get(JoinGameCommand::class)->willReturn($command);
    }

    public function testFactoryWithoutTemplate()
    {
        $factory = new JoinGameHandlerFactory();

        $this->assertInstanceOf(JoinGameHandlerFactory::class, $factory);

        $handler = $factory($this->container->reveal());

        $this->assertInstanceOf(JoinGameHandler::class, $handler);
    }
}
