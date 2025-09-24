<?php

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\KernelSubscriber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

#[CoversClass(KernelSubscriber::class)]
class KernelSubscriberTest extends TestCase
{
    public function testMemoryLimitBeingImposed(): void
    {
        $subscriber = new KernelSubscriber('512M');
        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = $this->createMock(Request::class);
        $event = new RequestEvent($kernel, $request, null);

        $subscriber->onRequestEvent($event);

        self::assertEquals('512M', ini_get('memory_limit'));
    }

    public function testMakeSureIsCovered(): void
    {
        self::assertIsArray(KernelSubscriber::getSubscribedEvents());
    }
}
