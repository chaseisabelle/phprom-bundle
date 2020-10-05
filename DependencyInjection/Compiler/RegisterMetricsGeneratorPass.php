<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler;

use ChaseIsabelle\PHPromBundle\Metrics\MetricsGeneratorRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterMetricsGeneratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(MetricsGeneratorRegistry::class)) {
            return;
        }

        $definition = $container->getDefinition(MetricsGeneratorRegistry::class);

        foreach ($container->findTaggedServiceIds('phprom_bundle.metrics_generator') as $id => $tags) {
            $generator = $container->getDefinition($id);
            $generator->addMethodCall('init', [
                $container->getParameter('phprom_bundle.namespace'),
                new Reference('phprom_bundle.collector_registry'),
            ]);
            $definition->addMethodCall('registerMetricsGenerator', [new Reference($id)]);
        }
    }
}
