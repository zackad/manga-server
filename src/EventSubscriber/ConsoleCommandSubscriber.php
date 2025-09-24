<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;

class ConsoleCommandSubscriber implements EventSubscriberInterface
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        #[Autowire('%env(APP_MEMORY_LIMIT)%')]
        private readonly string $memoryLimit,
    ) {
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $commandName = $event->getCommand()?->getName();
        $output = $event->getOutput();

        if ('messenger:setup-transports' === $commandName) {
            $output->writeln('<info> [INFO] Creating app data directory.</info>');
            $filesystem = new Filesystem();
            $filesystem->mkdir($this->projectDir.'/var/data');
        }
    }

    public function setMemoryLimit(): void
    {
        ini_set('memory_limit', $this->memoryLimit);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleCommandEvent::class => [
                ['onConsoleCommand'],
                ['setMemoryLimit'],
            ],
        ];
    }
}
