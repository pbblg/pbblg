<?php

namespace App\Action;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Domain\AccessToken\Generator;
use App\WebSocket\Client;

class LoginActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new LoginAction(
            $container->get(TemplateRendererInterface::class),
            $container->get(LoginInputFilter::class),
            $container->get(Generator::class),
            $container->get('User\Infrastructure\Repository'),
            $container->get(Client::class)
        );
    }
}
