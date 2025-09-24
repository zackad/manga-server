<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class KernelSubscriber implements EventSubscriberInterface
{
    public function __construct(
        #[Autowire('%env(APP_MEMORY_LIMIT)%')]
        private readonly string $memoryLimit,
    ) {
    }

    public function onRequestEvent(RequestEvent $event): void
    {
        ini_set('memory_limit', $this->memoryLimit);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onRequestEvent',
        ];
    }
}
