<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler;

use ChaseIsabelle\PHPromBundle\EventListener\RequestCounterListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IgnoredRoutesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(RequestCounterListener::class)) {
            return;
        }

        $ignoredRoutes = $container->getParameter('phprom_bundle.ignored_routes');
        $container->getDefinition(RequestCounterListener::class)->addArgument($ignoredRoutes);
    }
}
