<?php

namespace ChaseIsabelle\PHPromBundle\Service;

use Exception;
use PHProm\Counter;
use PHProm\Gauge;
use PHProm\Histogram;
use PHProm\PHProm;
use PHProm\Summary;

/**
 * @package ChaseIsabelle\PHPromBundle\Service
 */
class PHPromService
{
    /**
     * @var array<PHProm>
     */
    protected static $instances = [];

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @param string $address
     * @param string $namespace
     */
    public function __construct(string $address, string $namespace)
    {
        if (!array_key_exists($address, self::$instances)) {
            self::$instances[$address] = new PHProm($address);
        }

        $this->address   = $address;
        $this->namespace = $namespace;
    }

    /**
     * @return PHProm
     * @throws Exception
     */
    public function instance(): PHProm
    {
        $phprom = self::$instances[$this->address] ?? null;

        if (!$phprom) {
            throw new Exception('no instance with address ' . $this->address);
        }

        return $phprom;
    }

    /**
     * @return Counter
     * @throws Exception
     */
    public function counter(): Counter
    {
        return (new Counter($this->instance()))
            ->setNamespace($this->namespace);
    }

    /**
     * @return Histogram
     * @throws Exception
     */
    public function histogram(): Histogram
    {
        return (new Histogram($this->instance()))
            ->setNamespace($this->namespace);
    }

    /**
     * @return Summary
     * @throws Exception
     */
    public function summary(): Summary
    {
        return (new Summary($this->instance()))
            ->setNamespace($this->namespace);
    }

    /**
     * @return Gauge
     * @throws Exception
     */
    public function gauge(): Gauge
    {
        return (new Gauge($this->instance()))
            ->setNamespace($this->namespace);
    }
}
