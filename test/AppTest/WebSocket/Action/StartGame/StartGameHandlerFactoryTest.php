<?php

namespace AppTest\WebSocket\Action\StartGame;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\WebSocket\Action\StartGame\StartGameHandlerFactory;
use App\WebSocket\Action\StartGame\StartGameHandler;
use App\WebSocket\Client;

class StartGameHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $gameRepository = $this->prophesize(RepositoryInterface::class);
        $usersInGamesRepository = $this->prophesize(RepositoryInterface::class);
        $userRepository = $this->prophesize(RepositoryInterface::class);
        $webSocketClient = $this->prophesize(Client::class);

        $this->container->get('Game\Infrastructure\Repository')->willReturn($gameRepository);
        $this->container->get('UsersInGames\Infrastructure\Repository')->willReturn($usersInGamesRepository);
        $this->container->get('User\Infrastructure\Repository')->willReturn($userRepository);
        $this->container->get(Client::class)->willReturn($webSocketClient);
    }

    public function testFactoryWithoutTemplate()
    {
        $factory = new StartGameHandlerFactory();

        $this->assertInstanceOf(StartGameHandlerFactory::class, $factory);

        $handler = $factory($this->container->reveal());

        $this->assertInstanceOf(StartGameHandler::class, $handler);
    }
}
