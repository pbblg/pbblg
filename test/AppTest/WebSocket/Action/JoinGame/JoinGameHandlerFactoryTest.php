<?php

namespace AppTest\WebSocket\Action\JoinGame;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\JoinGame\JoinGameHandlerFactory;
use App\WebSocket\Action\JoinGame\JoinGameHandler;
use App\WebSocket\Client;

class JoinGameHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $gameRepository = $this->prophesize(RepositoryInterface::class);
        $usersInGamesRepository = $this->prophesize(RepositoryInterface::class);
        $webSocketClient = $this->prophesize(Client::class);

        $this->container->get('Game\Infrastructure\Repository')->willReturn($gameRepository);
        $this->container->get('UsersInGames\Infrastructure\Repository')->willReturn($usersInGamesRepository);
        $this->container->get(Client::class)->willReturn($webSocketClient);
    }

    public function testFactoryWithoutTemplate()
    {
        $factory = new JoinGameHandlerFactory();

        $this->assertInstanceOf(JoinGameHandlerFactory::class, $factory);

        $handler = $factory($this->container->reveal());

        $this->assertInstanceOf(JoinGameHandler::class, $handler);
    }
}
