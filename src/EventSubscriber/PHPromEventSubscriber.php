<?php

namespace ChaseIsabelle\PHPromBundle\EventSubscriber;

use ChaseIsabelle\PHPromBundle\Service\PHPromService;
use Exception;
use PHProm\Histogram;
use PHProm\Timer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @package ChaseIsabelle\PHPromBundle\EventSubscriber
 */
class PHPromEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var Timer
     */
    protected $_timer;

    /**
     * @var ResponseEvent
     */
    protected $_responseEvent;

    /**
     * @param PHPromService $phpromService
     */
    public function __construct(PHPromService $phpromService)
    {
        $histogram = (new Histogram($phpromService->getPHProm()))
            ->setNamespace($phpromService->getNamespace())
            ->setName('request')
            ->setDescription('incoming http request latencies')
            ->setLabels(['route', 'status']);

        $this->_timer = new Timer($histogram);
    }

    /**
     * @return array|void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST        => 'onKernelRequest',
            KernelEvents::RESPONSE       => 'onKernelResponse',
            KernelEvents::FINISH_REQUEST => 'onKernelFinishRequest'
        ];
    }

    /**
     * @param RequestEvent $requestEvent
     */
    public function onKernelRequest(RequestEvent $requestEvent)
    {
        $this->_timer->start();
    }

    /**
     * @param ResponseEvent $responseEvent
     */
    public function onKernelResponse(ResponseEvent $responseEvent)
    {
        $this->_responseEvent = $responseEvent;
    }

    /**
     * @param FinishRequestEvent $finishRequestEvent
     * @throws Exception
     */
    public function onKernelFinishRequest(FinishRequestEvent $finishRequestEvent)
    {
        $route  = $this->_responseEvent->getRequest()->attributes->get('_route') ?? '?';
        $status = '?';

        if ($this->_responseEvent) {
            $status = $this->_responseEvent->getResponse()->getStatusCode();
        }

        $this->_timer->stop()->record([
            'route'  => $route,
            'status' => $status
        ]);
    }
}
