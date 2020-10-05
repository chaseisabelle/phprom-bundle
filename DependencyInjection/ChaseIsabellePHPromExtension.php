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
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('phprom_bundle.address', $config['address']);
        $container->setParameter('phprom_bundle.namespace', $config['namespace']);
//        $container->setParameter('phprom_bundle.type', $config['type']);
//        if ('redis' === $config['type']) {
//            $container->setParameter('phprom_bundle.redis', $config['redis']);
//        }
        $container->setParameter('phprom_bundle.routes', $config['routes']);
        dd($config, $container);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

    }
}
