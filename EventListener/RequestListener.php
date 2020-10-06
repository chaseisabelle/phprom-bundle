<?php

declare(strict_types=1);

namespace ChaseIsabelle\PHPromBundle\EventListener;

use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use Exception;
use PHProm\Histogram;
use PHProm\PHProm;
use PHProm\Timer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

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
     * @var string
     */
    private $namespace;

    /**
     * @param PHPromService $phprom
     * @param string        $namespace
     * @param array         $routes
     */
    public function __construct(PHPromService $phprom, string $namespace, array $routes = [])
    {
        $this->namespace = $namespace;
        $this->routes    = $routes;

        $histogram = (new Histogram($phprom->instance()))
            ->setNamespace($this->namespace)
            ->setName('request_latency_seconds')
            ->setDescription('incoming http request latencies')
            ->setLabels(['route', 'status']);

        $this->timer = (new Timer($histogram))
            ->start();
    }

    /**
     * @param RequestEvent $event
     * @throws Exception
     */
    public function onRequest(RequestEvent $event): void
    {
        if (!$this->should($event)) {
            return;
        }


    }

    /**
     * @param ResponseEvent $event
     * @throws Exception
     */
    public function onResponse(ResponseEvent $event): void
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
                $this->logger->warning('failed to record metric: ' . $exception->getMessage(), $labels);
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

        $route = $this->route($event);

        if (!$route) {
            return false;
        }

        if (!$this->routes) {
            return true;
        }

        foreach ($this->routes as $matcher) {
            if ($route !== $matcher && !@preg_match($matcher, $route)) {
                return true;
            }
        }

        return false;
    }
}
