<?php

namespace AppTest\WebSocket\Action\NewGame;

use App\WebSocket\Command\JoinGameCommand;
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
        $usersInGameRepository = $this->prophesize(RepositoryInterface::class);
        $webSocketClient = $this->prophesize(Client::class);
        $joinGameCommand = $this->prophesize(JoinGameCommand::class);

        $this->container->get('Game\Infrastructure\Repository')->willReturn($gameRepository);
        $this->container->get('UsersInGames\Infrastructure\Repository')->willReturn($usersInGameRepository);
        $this->container->get(Client::class)->willReturn($webSocketClient);
        $this->container->get(JoinGameCommand::class)->willReturn($joinGameCommand);
    }

    public function testFactoryWithoutTemplate()
    {
        $factory = new NewGameHandlerFactory();

        $this->assertInstanceOf(NewGameHandlerFactory::class, $factory);

        $handler = $factory($this->container->reveal());

        $this->assertInstanceOf(NewGameHandler::class, $handler);
    }
}
