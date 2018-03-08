<?php

namespace App\Action\Admin\Games;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ListActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ListAction(
            $container->get(TemplateRendererInterface::class)
        );
    }
}
