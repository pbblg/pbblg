<?php

namespace AppTest\WebSocket\Action\NewGame;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\NewGame\NewGameHandlerFactory;
use App\WebSocket\Action\NewGame\NewGameHandler;
use App\WebSocket\Client;

class NewGameHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $gameRepository = $this->prophesize(RepositoryInterface::class);
        $webSocketClient = $this->prophesize(Client::class);

        $this->container->get('Game\Infrastructure\Repository')->willReturn($gameRepository);
        $this->container->get(Client::class)->willReturn($webSocketClient);
    }

    public function testFactoryWithoutTemplate()
    {
        $factory = new NewGameHandlerFactory();

        $this->assertInstanceOf(NewGameHandlerFactory::class, $factory);

        $handler = $factory($this->container->reveal());

        $this->assertInstanceOf(NewGameHandler::class, $handler);
    }
}
