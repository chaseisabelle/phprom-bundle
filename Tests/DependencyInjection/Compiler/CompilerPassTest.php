<?php

namespace Tests\ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler;

use ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler\CompilerPass;
use ChaseIsabelle\PHPromBundle\EventListener\RequestListener;
use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tests\ChaseIsabelle\PHPromBundle\PHPromTestCase;

/**
 * @package Tests\ChaseIsabelle\PHPromBundle\DependencyInjection\Compiler
 */
class CompilerPassTest extends PHPromTestCase
{
    /**
     * success case
     */
    public function testProcess_success()
    {
        $index     = 0;
        $address   = 'poop';
        $namespace = 'plop';
        $routes    = ['peepee'];
        $pass      = new CompilerPass();
        $container = $this->createMock(ContainerBuilder::class);
        $service   = $this->createMock(Definition::class);
        $listener  = $this->createMock(Definition::class);

        $container->expects($this->at($index++))
            ->method('hasDefinition')
            ->with(PHPromService::class)
            ->willReturn(true);

        $container->expects($this->at($index++))
            ->method('getDefinition')
            ->willReturn($service);

        $container->expects($this->at($index++))
            ->method('getParameter')
            ->with('phprom.address')
            ->willReturn($address);

        $service->expects($this->once())
            ->method('setArguments')
            ->with([$address]);

        $container->expects($this->at($index++))
            ->method('hasDefinition')
            ->with(RequestListener::class)
            ->willReturn(true);

        $container->expects($this->at($index++))
            ->method('getDefinition')
            ->with(RequestListener::class)
            ->willReturn($listener);

        $container->expects($this->at($index++))
            ->method('getParameter')
            ->with('phprom.namespace')
            ->willReturn($namespace);

        $listener->expects($this->at(0))
            ->method('setArgument')
            ->with('$namespace', $namespace);

        $container->expects($this->at($index++))
            ->method('getParameter')
            ->with('phprom.routes')
            ->willReturn($routes);

        $listener->expects($this->at(1))
            ->method('setArgument')
            ->with('$routes', $routes);

        $pass->process($container);
    }

    /**
     * empty route
     */
    public function testProcess_emptyRoute()
    {
        $index     = 0;
        $address   = 'poop';
        $namespace = 'plop';
        $routes    = [' '];
        $pass      = new CompilerPass();
        $container = $this->createMock(ContainerBuilder::class);
        $service   = $this->createMock(Definition::class);
        $listener  = $this->createMock(Definition::class);

        $container->expects($this->at($index++))
            ->method('hasDefinition')
            ->with(PHPromService::class)
            ->willReturn(true);

        $container->expects($this->at($index++))
            ->method('getDefinition')
            ->willReturn($service);

        $container->expects($this->at($index++))
            ->method('getParameter')
            ->with('phprom.address')
            ->willReturn($address);

        $service->expects($this->once())
            ->method('setArguments')
            ->with([$address]);

        $container->expects($this->at($index++))
            ->method('hasDefinition')
            ->with(RequestListener::class)
            ->willReturn(true);

        $container->expects($this->at($index++))
            ->method('getDefinition')
            ->with(RequestListener::class)
            ->willReturn($listener);

        $container->expects($this->at($index++))
            ->method('getParameter')
            ->with('phprom.namespace')
            ->willReturn($namespace);

        $listener->expects($this->once())
            ->method('setArgument')
            ->with('$namespace', $namespace);

        $container->expects($this->at($index++))
            ->method('getParameter')
            ->with('phprom.routes')
            ->willReturn($routes);

        $this->expectException(Exception::class);

        $pass->process($container);
    }
}
