<?php

namespace App\Action\Register;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Command\Register\RegisterCommand;
use App\Domain\AccessToken\Generator;
use App\WebSocket\Client;

class RegisterActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RegisterAction(
            $container->get(TemplateRendererInterface::class),
            $container->get(RegisterInputFilter::class),
            $container->get(RegisterCommand::class),
            $container->get(Generator::class),
            $container->get(Client::class),
            $container->get('User\Infrastructure\Repository')
        );
    }
}
