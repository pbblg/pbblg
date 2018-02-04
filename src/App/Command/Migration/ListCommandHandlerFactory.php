<?php

namespace App\Command\Migration;

use Psr\Container\ContainerInterface;

class ListCommandHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return ListCommandHandler
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return new ListCommandHandler(
            $config['migration']
        );
    }
}
