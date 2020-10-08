<?php

namespace Tests\ChaseIsabelle\PHPromBundle\Controller;

use ChaseIsabelle\PHPromBundle\Controller\MetricsController;
use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use Exception;
use PHProm\PHProm;
use Symfony\Component\HttpFoundation\Response;
use Tests\ChaseIsabelle\PHPromBundle\PHPromTestCase;

/**
 * @package Tests\ChaseIsabelle\PHPromBundle\Controller
 */
class MetricsControllerTest extends PHPromTestCase
{
    /**
     * success
     */
    public function testMetrics_success()
    {
        $metrics    = 'poop';
        $phprom     = $this->createMock(PHProm::class);
        $service    = $this->createMock(PHPromService::class);
        $controller = new MetricsController($service);
        $response   = new Response($metrics, 200, [
            'Content-Type' => 'text/plain; version=0.0.4'
        ]);

        $phprom->expects($this->once())
            ->method('get')
            ->willReturn($metrics);

        $service->expects($this->once())
            ->method('instance')
            ->willReturn($phprom);

        $this->assertEquals($response, $controller->metrics());
    }

    /**
     * failure
     */
    public function testMetrics_failure()
    {
        $phprom   = $this->createMock(PHProm::class);
        $service  = $this->createMock(PHPromService::class);
        $exception = new Exception('aw crap');

        $phprom->expects($this->once())
            ->method('get')
            ->willThrowException($exception);

        $service->expects($this->once())
            ->method('instance')
            ->willReturn($phprom);

        $controller = new MetricsController($service);

        $this->expectException(Exception::class);

        $controller->metrics();
    }
}
