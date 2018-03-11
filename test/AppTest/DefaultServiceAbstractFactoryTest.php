<?php

namespace AppTest;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use App\DefaultServiceAbstractFactory;

class DefaultServiceAbstractFactoryTest extends TestCase
{
    public function testCanCreateBadService()
    {
        $factory = new DefaultServiceAbstractFactory();

        $result = $factory->canCreate(
            $this->prophesize(ContainerInterface::class)->reveal(),
            'BuzzService'
        );

        $this->assertFalse($result);
    }

    public function testCanCreate()
    {
        $factory = new DefaultServiceAbstractFactory();

        $result = $factory->canCreate(
            $this->prophesize(ContainerInterface::class)->reveal(),
            SomeService::class
        );

        $this->assertTrue($result);
    }

    public function testCreatingWithFactory()
    {
        $factory = new DefaultServiceAbstractFactory();

        $result = $factory(
            $this->prophesize(ContainerInterface::class)->reveal(),
            SomeService::class
        );

        $this->assertInstanceOf(SomeService::class, $result);
    }

    public function testInvokableCreating()
    {
        $factory = new DefaultServiceAbstractFactory();

        $result = $factory(
            $this->prophesize(ContainerInterface::class)->reveal(),
            InvokableService::class
        );

        $this->assertInstanceOf(InvokableService::class, $result);
    }
}

class SomeServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new SomeService();
    }
}

class SomeService
{

}

class InvokableService
{

}
