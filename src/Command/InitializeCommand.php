<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\ReIndexMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:initialize',
    description: 'Dispatch background job on app initialization',
)]
class InitializeCommand extends Command
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('INFO: Trigger indexing job');
        $message = new ReIndexMessage();
        $this->messageBus->dispatch($message);

        return Command::SUCCESS;
    }
}
