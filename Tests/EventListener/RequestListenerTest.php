<?php

namespace Tests\ChaseIsabelle\PHPromBundle\EventListener;

use ChaseIsabelle\PHPromBundle\EventListener\RequestListener;
use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use PHProm\Histogram;
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
        $route      = 'hulkamania';
        $routes     = [$route];
        $status     = 200;
        $labels     = ['route' => $route, 'status' => $status];
        $name       = 'request_latency_seconds';
        $service    = $this->createMock(PHPromService::class);
        $histogram  = $this->createMock(Histogram::class);
        $event      = $this->createMock(TerminateEvent::class);
        $request    = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $response   = $this->createMock(Response::class);

        $service->expects($this->once())
            ->method('histogram')
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setName')
            ->with($name)
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setDescription')
            ->with($this->isType('string'))
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setLabels')
            ->with(array_keys($labels))
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('record')
            ->with($this->isType('float'), $labels);

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
            ->willReturn($response);

        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn($status);

        $listener = new RequestListener($service, $routes);

        $listener->onTerminate($event);
    }

    /**
     * do a full-pass test
     */
    public function testNoRoutes_success()
    {
        $route      = 'undertaker';
        $routes     = [];
        $status     = 200;
        $labels     = ['route' => $route, 'status' => $status];
        $name       = 'request_latency_seconds';
        $service    = $this->createMock(PHPromService::class);
        $histogram  = $this->createMock(Histogram::class);
        $event      = $this->createMock(TerminateEvent::class);
        $request    = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $resposne   = $this->createMock(Response::class);

        $service->expects($this->once())
            ->method('histogram')
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setName')
            ->with($name)
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setDescription')
            ->with($this->isType('string'))
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setLabels')
            ->with(array_keys($labels))
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('record')
            ->with($this->isType('float'), $labels);

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

        $listener = new RequestListener($service, $routes);

        $listener->onTerminate($event);
    }

    /**
     * do a full-pass test
     */
    public function testNotMasterRequest_success()
    {
        $name      = 'request_latency_seconds';
        $labels    = ['route', 'status'];
        $route     = 'stone_cold_steve_austin';
        $routes    = [$route];
        $service   = $this->createMock(PHPromService::class);
        $histogram = $this->createMock(Histogram::class);
        $event     = $this->createMock(TerminateEvent::class);

        $service->expects($this->once())
            ->method('histogram')
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setName')
            ->with($name)
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setDescription')
            ->with($this->isType('string'))
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setLabels')
            ->with($labels)
            ->willReturn($histogram);

        $histogram->expects($this->never())
            ->method('record');

        $event->expects($this->once())
            ->method('isMasterRequest')
            ->willReturn(false);

        $event->expects($this->never())
            ->method('getRequest');

        $event->expects($this->never())
            ->method('getResponse');

        $listener = new RequestListener($service, $routes);

        $listener->onTerminate($event);
    }

    /**
     * do a full-pass test
     */
    public function testRegexRoute_success()
    {
        $route      = 'andre_the_giant';
        $routes     = ['/^[aA][^t]+t/'];
        $status     = 200;
        $labels     = ['route' => $route, 'status' => $status];
        $name       = 'request_latency_seconds';
        $service    = $this->createMock(PHPromService::class);
        $histogram  = $this->createMock(Histogram::class);
        $event      = $this->createMock(TerminateEvent::class);
        $request    = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $resposne   = $this->createMock(Response::class);

        $service->expects($this->once())
            ->method('histogram')
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setName')
            ->with($name)
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setDescription')
            ->with($this->isType('string'))
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('setLabels')
            ->with(array_keys($labels))
            ->willReturn($histogram);

        $histogram->expects($this->once())
            ->method('record')
            ->with($this->isType('float'), $labels);

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

        $listener = new RequestListener($service, $routes);

        $listener->onTerminate($event);
    }
}
