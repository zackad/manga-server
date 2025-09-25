<?php

declare(strict_types=1);

namespace App\Message;

use App\Cache\Indexer;

final class ReIndexMessage
{
    public function __toString()
    {
        return Indexer::CACHE_KEY;
    }
}
