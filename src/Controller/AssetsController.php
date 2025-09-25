<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MimeGuesser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AssetsController extends AbstractController
{
    #[Route('/build/{assets}', name: 'app_assets', requirements: ['assets' => '.+'], methods: ['GET'])]
    public function index(#[Autowire('%kernel.project_dir%')] string $projectRoot, MimeGuesser $guesser, string $assets): Response
    {
        /** @psalm-suppress PossiblyNullOperand */
        $file = $projectRoot.'/public/build/'.$assets;

        if (!file_exists($file)) {
            throw $this->createNotFoundException(sprintf('File "%s" not found.', $file));
        }

        $stream = new Stream($file);
        $response = new BinaryFileResponse($stream);
        $response->headers->add(['Content-Type' => $guesser->guessMimeType($file)]);
        $response->setExpires(new \DateTime('+1 week'));

        return $response;
    }
}
