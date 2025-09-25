<?php

declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Cache\Indexer;
use App\Message\ReIndexMessage;
use App\MessageHandler\ReIndexMessageHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(ReIndexMessage::class)]
#[CoversClass(ReIndexMessageHandler::class)]
class ReIndexMessageHandlerTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testReIndex(): void
    {
        $cache = self::getContainer()->get(CacheItemPoolInterface::class);
        $handler = $this->getContainer()->get(ReIndexMessageHandler::class);
        $message = new ReIndexMessage();

        // Cache has NOT been populated
        self::assertFalse($cache->hasItem(Indexer::CACHE_KEY));
        $handler->__invoke($message);

        // Cache has been populated
        self::assertTrue($cache->hasItem(Indexer::CACHE_KEY));
    }
}
