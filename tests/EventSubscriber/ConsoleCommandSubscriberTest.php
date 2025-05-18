<?php

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\ConsoleCommandSubscriber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(ConsoleCommandSubscriber::class)]
class ConsoleCommandSubscriberTest extends TestCase
{
    private string $rootDataDirectory;

    protected function setUp(): void
    {
        $this->rootDataDirectory = dirname(__DIR__, 2).'/var/test';
        $filesystem = new Filesystem();
        $filesystem->remove($this->rootDataDirectory);
    }

    public function testDataDirectoryIsCreatedWhenSetupMessengerTransport(): void
    {
        $command = new Command('messenger:setup-transports');
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);
        $event = new ConsoleCommandEvent($command, $input, $output);
        $subscriber = new ConsoleCommandSubscriber($this->rootDataDirectory);

        // Check if initial state is not exists
        self::assertDirectoryDoesNotExist($this->rootDataDirectory);
        $subscriber->onConsoleCommand($event);

        self::assertDirectoryExists($this->rootDataDirectory.'/var/data');
    }

    public function testDataDirectoryIsNotCreatedWhenOnOtherComman(): void
    {
        $command = new Command('about');
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);
        $event = new ConsoleCommandEvent($command, $input, $output);
        $subscriber = new ConsoleCommandSubscriber($this->rootDataDirectory);

        $subscriber->onConsoleCommand($event);

        self::assertDirectoryDoesNotExist($this->rootDataDirectory.'/var/data');
    }

    public function testMakeSureIsCovered(): void
    {
        self::assertIsArray(ConsoleCommandSubscriber::getSubscribedEvents());
    }

    protected function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->rootDataDirectory);
    }
}
