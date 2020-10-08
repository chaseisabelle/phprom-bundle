<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler;

use ChaseIsabelle\PHPromBundle\EventListener\RequestListener;
use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @package ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler
 */
class CompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition(PHPromService::class)) {
            $container->getDefinition(PHPromService::class)->setArguments([
                $container->getParameter('phprom.address')
            ]);
        }

        if ($container->hasDefinition(RequestListener::class)) {
            $definition = $container->getDefinition(RequestListener::class);

            $definition->setArgument(
                '$namespace',
                $container->getParameter('phprom.namespace')
            );

            $routes = $container->getParameter('phprom.routes');

            array_walk($routes, function ($route) {
                if (!trim($route)) {
                    throw new Exception('route cannot be empty');
                }
            });

            $definition->setArgument('$routes', $routes);
        }
    }
}
