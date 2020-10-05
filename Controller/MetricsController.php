<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\Controller;

use Exception;
use PHProm\PHProm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MetricsController.
 */
class MetricsController extends AbstractController
{
    /**
     * @var PHProm
     */
    protected $phprom;

    /**
     * @param PHProm $phprom
     */
    public function __construct(PHProm $phprom)
    {
        $this->phprom = $phprom;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function metrics()
    {
        return new Response($this->phprom->get(), 200, [
            'Content-Type' => 'text/plain; version=0.0.4'
        ]);
    }
}
