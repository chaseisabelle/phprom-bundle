<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler;

use ChaseIsabelle\PHPromBundle\EventListener\RequestListener;
use Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('phprom.client')) {
            $container->getDefinition('phprom.client')->setArguments([
                $container->getParameter('phprom.address')
            ]);
        }

        if ($container->hasDefinition(RequestListener::class)) {
            $definition = $container->getDefinition(RequestListener::class);

            $definition->setArgument(
                'phprom.namespace',
                $container->getParameter('phprom.namespace')
            );

            $routes = $container->getParameter('phprom.routes');

            array_walk($routes, function ($route) {
                if (!trim($route)) {
                    throw new Exception('route cannot be empty');
                }
            });

            $definition->setArgument('phprom.routes', $routes);
        }
    }
}
