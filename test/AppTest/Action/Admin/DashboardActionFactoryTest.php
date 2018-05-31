<?php

namespace AppTest\Action\Admin;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Action\Admin\DashboardActionFactory;
use App\Action\Admin\DashboardAction;

class DashboardActionFactoryTest extends TestCase
{
    /** @var ContainerInterface */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $renderer = $this->prophesize(TemplateRendererInterface::class);

        $this->container->get(TemplateRendererInterface::class)->willReturn($renderer);
    }

    public function testFactoryWithoutTemplate()
    {
        $factory = new DashboardActionFactory();

        $this->assertInstanceOf(DashboardActionFactory::class, $factory);

        $handler = $factory($this->container->reveal());

        $this->assertInstanceOf(DashboardAction::class, $handler);
    }
}
