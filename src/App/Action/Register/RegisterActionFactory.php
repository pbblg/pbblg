<?php

namespace App\Action\Register;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class RegisterActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RegisterAction(
            $container->get(TemplateRendererInterface::class),
            $container->get(RegisterInputFilter::class)
        );
    }
}
