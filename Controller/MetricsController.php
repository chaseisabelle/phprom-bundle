<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\Controller;

use ChaseIsabelle\PHPromBundle\Metrics\Renderer;

/**
 * Class MetricsController.
 */
class MetricsController
{
    /**
     * @var Renderer
     */
    private $renderer;

    public function __construct(Renderer $metricsRenderer)
    {
        $this->renderer = $metricsRenderer;
    }

    public function prometheus()
    {
        return $this->renderer->renderResponse();
    }
}
