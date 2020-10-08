<?php

namespace Tests\ChaseIsabelle\PHPromBundle\DependencyInjection;

use ChaseIsabelle\PHPromBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Tests\ChaseIsabelle\PHPromBundle\PHPromTestCase;

/**
 * @package Tests\ChaseIsabelle\PHPromBundle\DependencyInjection
 */
class ConfigurationTest extends PHPromTestCase
{
    /**
     * user has provided all configs
     */
    public function testGetConfigTreeBuilder_allProvided()
    {
        $address        = '1.2.3.4:1234';
        $namespace      = 'test';
        $routes         = ['poo', 'pee'];
        $configurations = [
            'address'   => $address,
            'namespace' => $namespace,
            'routes'    => $routes
        ];

        $configuration = new Configuration();
        $expect        = array_merge($configurations, []);
        $builder       = $configuration->getConfigTreeBuilder();
        $tree          = $builder->buildTree();
        $result        = $tree->finalize($configurations);

        $this->assertEquals($expect, $result);
    }

    /**
     * user has not provided defaults
     */
    public function testGetConfigTreeBuilder_defaults()
    {
        $address        = '127.0.0.1:3333';
        $namespace      = 'test';
        $routes         = [];
        $configurations = [
            'namespace' => $namespace
        ];
        $defaults       = [
            'address' => $address,
            'routes'  => $routes
        ];

        $configuration = new Configuration();
        $expect        = array_merge($configurations, $defaults);
        $builder       = $configuration->getConfigTreeBuilder();
        $tree          = $builder->buildTree();
        $result        = $tree->finalize($configurations);

        $this->assertEquals($expect, $result);
    }

    /**
     * user has not provided required
     */
    public function testGetConfigTreeBuilder_noRequired()
    {
        $address        = '127.0.0.1:3333';
        $routes         = [];
        $configurations = [];
        $defaults       = [
            'address' => $address,
            'routes'  => $routes
        ];

        $this->expectException(InvalidConfigurationException::class);

        $configuration = new Configuration();
        $expect        = array_merge($configurations, $defaults);
        $builder       = $configuration->getConfigTreeBuilder();
        $tree          = $builder->buildTree();
        $result        = $tree->finalize($configurations);

        $this->assertEquals($expect, $result);
    }
}
