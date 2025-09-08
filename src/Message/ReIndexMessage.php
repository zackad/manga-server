<?php

declare(strict_types=1);

namespace App\Message;

final class ReIndexMessage
{
    public function __toString()
    {
        return 'search-index';
    }
}
