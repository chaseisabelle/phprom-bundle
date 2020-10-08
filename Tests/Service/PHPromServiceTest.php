<?php

namespace Tests\ChaseIsabelle\PHPromBundle\Service;

use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use Exception;
use PHProm\PHProm;
use Tests\ChaseIsabelle\PHPromBundle\PHPromTestCase;

/**
 * @package Tests\ChaseIsabelle\PHPromBundle\Service
 */
class PHPromServiceTest extends PHPromTestCase
{
    /**
     * @throws Exception shouldnt be thrown
     */
    public function testInstance_success()
    {
        $address   = '127.0.0.1:3333';
        $namespace = 'big_giant_hamburger_feet';
        $phprom    = new PHProm($address);
        $service   = new PHPromService($address, $namespace);

        $this->assertEquals($phprom, $service->instance());
    }

    /**
     * @throws Exception shouldnt be thrown
     */
    public function testInstance_notEqual()
    {
        $address   = '127.0.0.1:3334';
        $namespace = 'am_i_a_turtle_or_a_clown';
        $phprom    = new PHProm('127.0.0.1:3333');
        $service   = new PHPromService($address, $namespace);

        $this->assertNotEquals($phprom, $service->instance());
    }

    /**
     * scenario should never happen, but why not test for it anyhow
     *
     * @throws Exception should be caught
     */
    public function testInstance_failure()
    {
        $address = '127.0.0.1:3333';
        $namespace = 'fat_tony';

        $service = new class ($address, $namespace) extends PHPromService {
            public function __construct(string $address, string $namespace)
            {
                // do not set
            }
        };

        $this->expectException(Exception::class);

        $service->instance();
    }
}
