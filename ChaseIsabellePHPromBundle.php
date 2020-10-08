<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle;

use ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler\CompilerPass;
use ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler\RegisterMetricsGeneratorPass;
use ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler\ResolveAdapterDefinitionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @package ChaseIsabelle\PHPromBundle
 */
class ChaseIsabellePHPromBundle extends Bundle
{
    /**
     * adds the custom compiler pass to the container
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CompilerPass());
    }

    /**
     * override the extension for custom config file and malformed name due
     * to camelcase converter
     *
     * @return false|ExtensionInterface|__anonymous@1024|null
     */
    public function getContainerExtension()
    {
        $extension = parent::getContainerExtension();

        return new class ($extension) implements ExtensionInterface {
            private $extension;

            public function __construct($extension)
            {
                $this->extension = $extension;
            }

            public function load(array $configs, ContainerBuilder $container)
            {
                $this->extension->load($configs, $container);
            }

            public function getNamespace()
            {
                return str_replace('chase_isabelle_ph_prom', 'phprom', $this->extension->getNamespace());
            }

            public function getXsdValidationBasePath()
            {
                return $this->extension->getXsdValidationBasePath();
            }

            public function getAlias()
            {
                return 'phprom';
            }
        };
    }
}
