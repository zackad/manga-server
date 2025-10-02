<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\InitializeCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

#[CoversClass(InitializeCommand::class)]
class InitializeCommandTest extends KernelTestCase
{
    public function testReindexMessageIsDispatced(): void
    {
        $kernel = self::createKernel();
        $application = new Application($kernel);
        $command = $application->find('app:initialize');
        $tester = new CommandTester($command);

        $exitCode = $tester->execute([]);
        $output = $tester->getDisplay();

        self::assertEquals(0, $exitCode);
        self::assertStringContainsString('INFO: Trigger indexing job', $output);
    }
}
