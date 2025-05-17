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

    public static function getSubscribedEvents(): array
    {
        return [
            'console.command' => 'onConsoleCommand',
        ];
    }
}
