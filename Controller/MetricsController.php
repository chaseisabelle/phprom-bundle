<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\Controller;

use ChaseIsabelle\PHPromBundle\Service\PHPromService;
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
     * @var PHPromService
     */
    protected $phprom;

    /**
     * @param PHPromService $phprom
     */
    public function __construct(PHPromService $phprom)
    {
        $this->phprom = $phprom;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function metrics()
    {
        return new Response($this->phprom->instance()->get(), 200, [
            'Content-Type' => 'text/plain; version=0.0.4'
        ]);
    }
}
