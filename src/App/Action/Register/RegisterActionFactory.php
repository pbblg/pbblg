<?php

namespace App\Action\Register;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Command\Register\RegisterCommand;
use App\WebSocket\Command\LoginCommand;

class RegisterActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RegisterAction(
            $container->get(TemplateRendererInterface::class),
            $container->get(RegisterInputFilter::class),
            $container->get(RegisterCommand::class),
            $container->get(LoginCommand::class)
        );
    }
}
