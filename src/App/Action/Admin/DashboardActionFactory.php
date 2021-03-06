<?php

namespace App\Action\Admin;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class DashboardActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new DashboardAction(
            $container->get(TemplateRendererInterface::class)
        );
    }
}
