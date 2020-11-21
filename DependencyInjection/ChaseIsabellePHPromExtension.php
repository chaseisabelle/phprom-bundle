<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ChaseIsabellePHPromExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configurations, ContainerBuilder $container)
    {
        $configuration  = new Configuration();
        $configurations = $this->processConfiguration($configuration, $configurations);

        $container->setParameter('phprom.address', $configurations['address']);
        $container->setParameter('phprom.namespace', $configurations['namespace']);
        $container->setParameter('phprom.api', $configurations['api']);
        $container->setParameter('phprom.routes', $configurations['routes']);

        (new Loader\XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        ))->load('services.xml');
    }
}
