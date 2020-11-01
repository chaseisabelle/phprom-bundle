<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\EventListener;

use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use Exception;
use PHProm\Histogram;
use PHProm\Timer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * Class RequestCounterListener.
 */
class RequestListener implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Timer
     */
    private $timer;

    /**
     * @var array
     */
    private $routes;

    /**
     * @param PHPromService $phprom
     * @param array         $routes
     * @throws Exception
     */
    public function __construct(PHPromService $phprom, array $routes = [])
    {
        $this->routes = $routes;

        $histogram = $phprom->histogram()
            ->setName('request_latency_seconds')
            ->setDescription('incoming http request latencies')
            ->setLabels(['route', 'status']);

        $this->timer = (new Timer($histogram))
            ->start();
    }

    /**
     * @param TerminateEvent $event
     */
    public function onTerminate(TerminateEvent $event): void
    {
        if (!$this->should($event)) {
            return;
        }

        $labels = [
            'route'  => $this->route($event),
            'status' => $event->getResponse()->getStatusCode()
        ];

        try {
            $this->timer->stop()->record($labels);
        } catch (Exception $exception) {
            if ($this->logger) {
                $this->logger->warning('phprom failure: ' . $exception->getMessage(), $labels);
            }
        }
    }

    /**
     * @param KernelEvent $event
     * @return string|null
     */
    protected function route(KernelEvent $event): ?string
    {
        return $event->getRequest()->attributes->get('_route') ?? null;
    }

    /**
     * @param KernelEvent $event
     * @return bool
     */
    protected function should(KernelEvent $event): bool
    {
        if (!$event->isMasterRequest()) {
            return false;
        }

        if (!$this->routes) {
            return true;
        }

        $route = $this->route($event);

        if (!$route) {
            return false;
        }

        foreach ($this->routes as $matcher) {
            if ($route === $matcher || @preg_match($matcher, $route)) {
                return true;
            }
        }

        return false;
    }
}
