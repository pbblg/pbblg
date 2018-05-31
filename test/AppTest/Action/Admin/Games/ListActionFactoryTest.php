<?php

namespace AppTest\Action\Admin\Games;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use T4webDomainInterface\Infrastructure\RepositoryInterface;
use App\Action\Admin\Games\ListActionFactory;
use App\Action\Admin\Games\ListAction;

class ListActionFactoryTest extends TestCase
{
    /** @var ContainerInterface */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $gameRepository = $this->prophesize(RepositoryInterface::class);
        $userRepository = $this->prophesize(RepositoryInterface::class);
        $usersInGamesRepository = $this->prophesize(RepositoryInterface::class);
        $renderer = $this->prophesize(TemplateRendererInterface::class);

        $this->container->get('Game\Infrastructure\Repository')->willReturn($gameRepository);
        $this->container->get('User\Infrastructure\Repository')->willReturn($userRepository);
        $this->container->get('UsersInGames\Infrastructure\Repository')->willReturn($usersInGamesRepository);
        $this->container->get(TemplateRendererInterface::class)->willReturn($renderer);
    }

    public function testFactoryWithoutTemplate()
    {
        $factory = new ListActionFactory();

        $this->assertInstanceOf(ListActionFactory::class, $factory);

        $handler = $factory($this->container->reveal());

        $this->assertInstanceOf(ListAction::class, $handler);
    }
}
