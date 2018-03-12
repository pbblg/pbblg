<?php

namespace App\Action\Admin\Users;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ListActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ListAction(
            $container->get('User\Infrastructure\Repository'),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
