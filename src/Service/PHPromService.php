<?php

namespace ChaseIsabelle\PHPromBundle\Service;

use PHProm\PHProm;

/**
 * @package ChaseIsabelle\PHPromBundle\Service
 */
class PHPromService
{
    /**
     * @var string
     */
    protected $_address;

    /**
     * @var string
     */
    protected $_namespace;

    /**
     * @var PHProm
     */
    protected $_phprom;

    /**
     * @param string $address
     * @param string $namespace
     */
    public function __construct(string $address, string $namespace)
    {
        $this->_address   = $address;
        $this->_namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->_namespace;
    }

    /**
     * @return PHProm
     */
    public function getPHProm(): PHProm
    {
        if (!$this->_phprom) {
            $this->_phprom = new PHProm($this->_address);
        }

        return $this->_phprom;
    }
}
