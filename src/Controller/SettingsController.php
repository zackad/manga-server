<?php

declare(strict_types=1);

namespace App\Controller;

use App\Message\ReIndexMessage;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{
    #[Route(path: '/settings', name: 'app_settings')]
    public function index(KernelInterface $kernel): Response
    {
        $console = new Application($kernel);
        $input = new ArrayInput(['command' => 'about']);
        $output = new BufferedOutput();
        $console->setAutoExit(false);
        $console->run($input, $output);
        $about = $output->fetch();

        return $this->render('settings.html.twig', [
            'about' => $about,
        ]);
    }

    #[Route(path: '/settings/reindex', name: 'app_settings_reindex', methods: ['POST'])]
    public function reIndex(MessageBusInterface $bus): Response
    {
        $message = new ReIndexMessage();
        $bus->dispatch($message);

        return $this->redirectToRoute('app_settings');
    }
}
