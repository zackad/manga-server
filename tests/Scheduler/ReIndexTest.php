<?php

declare(strict_types=1);

namespace App\Tests\Scheduler;

use App\Scheduler\ReIndex;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(ReIndex::class)]
class ReIndexTest extends KernelTestCase
{
    public function testReIndex(): void
    {
        self::bootKernel();
        $scheduler = self::getContainer()->get(ReIndex::class)->getSchedule();
        $messages = $scheduler->getRecurringMessages()[0];
        $nextRunDate = $messages->getTrigger()->getNextRunDate(new \DateTimeImmutable());
        self::assertEquals('02:26', $nextRunDate->format('H:i'));
    }
}
