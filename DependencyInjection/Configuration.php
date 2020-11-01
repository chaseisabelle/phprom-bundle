<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\DependencyInjection;

use PHProm\PHProm;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('phprom');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('address')
                    ->cannotBeEmpty()
                    ->defaultValue('127.0.0.1:3333')
                ->end()
                ->scalarNode('namespace')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('api')
                    ->cannotBeEmpty()
                    ->defaultValue(PHProm::GRPC_API)
                ->end()
                ->arrayNode('routes')
                    ->prototype('scalar')->end()
                    ->defaultValue([])
                ->end()
            ->end();

        return $treeBuilder;
    }
}
