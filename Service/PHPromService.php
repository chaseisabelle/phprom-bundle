<?php

namespace ChaseIsabelle\PHPromBundle\Service;

use Exception;
use PHProm\PHProm;

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
     * @param string $address
     */
    public function __construct(string $address)
    {
        if (!array_key_exists($address, self::$instances)) {
            self::$instances[$address] = new PHProm($address);
        }

        $this->address = $address;
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
}
