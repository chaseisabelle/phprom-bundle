<?php

namespace Tests\ChaseIsabelle\PHPromBundle\EventListener;

use ChaseIsabelle\PHPromBundle\EventListener\RequestListener;
use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use PHProm\PHProm;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Tests\ChaseIsabelle\PHPromBundle\PHPromTestCase;

/**
 * @package Tests\ChaseIsabelle\PHPromBundle\EventListener
 */
class RequestListenerTest extends PHPromTestCase
{
    /**
     * do a full-pass test
     */
    public function testAll_success()
    {
        $namespace = 'macho_man_randy_savage';
        $route     = 'hulkamania';
        $routes    = [$route];
        $status    = 200;
        $labels    = ['route' => $route, 'status' => $status];
        $name      = 'request_latency_seconds';
        $phprom    = $this->createMock(PHProm::class);
        $service   = $this->createMock(PHPromService::class);
        $event     = $this->createMock(TerminateEvent::class);
        $request   = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $resposne = $this->createMock(Response::class);

        $phprom->expects($this->once())
            ->method('registerHistogram')
            ->with(
                $namespace,
                $name,
                $this->isType('string'),
                array_keys($labels),
                []
            )
            ->willReturn(true);

        $phprom->expects($this->once())
            ->method('recordHistogram')
            ->with(
                $namespace,
                $name,
                $this->isType('float'),
                $labels
            );

        $service->expects($this->once())
            ->method('instance')
            ->willReturn($phprom);

        $event->expects($this->once())
            ->method('isMasterRequest')
            ->willReturn(true);

        $event->expects($this->any())
            ->method('getRequest')
            ->willReturn($request);

        $attributes->expects($this->any())
            ->method('get')
            ->with('_route')
            ->willReturn($route);

        $request->attributes = $attributes;

        $event->expects($this->once())
            ->method('getResponse')
            ->willReturn($resposne);

        $resposne->expects($this->once())
            ->method('getStatusCode')
            ->willReturn($status);

        $listener = new RequestListener($service, $namespace, $routes);

        $listener->onTerminate($event);
    }

    /**
     * do a full-pass test
     */
    public function testNoRoutes_success()
    {
        $namespace = 'kane';
        $route     = 'undertaker';
        $routes    = [];
        $status    = 200;
        $labels    = ['route' => $route, 'status' => $status];
        $name      = 'request_latency_seconds';
        $phprom    = $this->createMock(PHProm::class);
        $service   = $this->createMock(PHPromService::class);
        $event     = $this->createMock(TerminateEvent::class);
        $request   = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $resposne = $this->createMock(Response::class);

        $phprom->expects($this->once())
            ->method('registerHistogram')
            ->with(
                $namespace,
                $name,
                $this->isType('string'),
                array_keys($labels),
                []
            )
            ->willReturn(true);

        $phprom->expects($this->once())
            ->method('recordHistogram')
            ->with(
                $namespace,
                $name,
                $this->isType('float'),
                $labels
            );

        $service->expects($this->once())
            ->method('instance')
            ->willReturn($phprom);

        $event->expects($this->once())
            ->method('isMasterRequest')
            ->willReturn(true);

        $event->expects($this->any())
            ->method('getRequest')
            ->willReturn($request);

        $attributes->expects($this->any())
            ->method('get')
            ->with('_route')
            ->willReturn($route);

        $request->attributes = $attributes;

        $event->expects($this->once())
            ->method('getResponse')
            ->willReturn($resposne);

        $resposne->expects($this->once())
            ->method('getStatusCode')
            ->willReturn($status);

        $listener = new RequestListener($service, $namespace, $routes);

        $listener->onTerminate($event);
    }

    /**
     * do a full-pass test
     */
    public function testNotMasterRequest_success()
    {
        $namespace = 'the_rock';
        $route     = 'stone_cold_steve_austin';
        $routes    = [$route];
        $phprom    = $this->createMock(PHProm::class);
        $service   = $this->createMock(PHPromService::class);
        $event     = $this->createMock(TerminateEvent::class);

        $phprom->expects($this->never())
            ->method('registerHistogram');

        $phprom->expects($this->never())
            ->method('recordHistogram');

        $service->expects($this->once())
            ->method('instance')
            ->willReturn($phprom);

        $event->expects($this->once())
            ->method('isMasterRequest')
            ->willReturn(false);

        $event->expects($this->never())
            ->method('getRequest');

        $event->expects($this->never())
            ->method('getResponse');

        $listener = new RequestListener($service, $namespace, $routes);

        $listener->onTerminate($event);
    }
}
