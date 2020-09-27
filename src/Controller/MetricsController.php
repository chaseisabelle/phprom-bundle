<?php

namespace ChaseIsabelle\PHPromBundle\Controller;

use Exception;
use PHProm\PHProm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package ChaseIsabelle\PHPromBundle\Controller
 */
class MetricsController extends AbstractController
{
    /**
     * @var PHProm
     */
    private $phprom = null;

    /**
     * MetricsController constructor.
     *
     * @param PHProm $phprom
     */
    public function __construct(PHProm $phprom)
    {
        $this->phprom = $phprom;
    }

    /**
     * @Route("/metrics")
     * @return Response
     * @throws Exception
     */
    public function metrics(): Response
    {
        return new Response($this->phprom->get(), 200);
    }
}
