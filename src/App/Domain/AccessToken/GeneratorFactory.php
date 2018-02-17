<?php

namespace App\Domain\AccessToken;

use Psr\Container\ContainerInterface;
use App\Domain\User;

class GeneratorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Generator(
            $container->get(Repository::class),
            $container->get(User\Repository::class)
        );
    }
}
