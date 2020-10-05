<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler;

use Prometheus\Storage\APC;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\Redis;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveAdapterDefinitionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('phprom_bundle.adapter')) {
            return;
        }

        $adapterClasses = [
            'in_memory' => InMemory::class,
            'apcu' => APC::class,
            'redis' => Redis::class,
        ];

        $definition = $container->getDefinition('phprom_bundle.adapter');
        $definition->setAbstract(false);
        $definition->setClass($adapterClasses[$container->getParameter('phprom_bundle.type')]);
        if ('redis' === $container->getParameter('phprom_bundle.type')) {
            $definition->setArguments([$container->getParameter('phprom_bundle.redis')]);
        }
    }
}
