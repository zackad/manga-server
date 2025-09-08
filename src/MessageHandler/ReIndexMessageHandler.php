<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Cache\Indexer;
use App\Message\ReIndexMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\CacheInterface;

#[AsMessageHandler]
final class ReIndexMessageHandler
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly Indexer $indexer,
    ) {
    }

    public function __invoke(ReIndexMessage $message): void
    {
        $this->cache->get((string) $message, [$this->indexer, 'buildIndex']);
    }
}
