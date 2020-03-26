<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\Routing\Annotation\Route;

class AssetsController extends AbstractController
{
    /**
     * @Route("/build/{assets}", name="assets", methods={"GET"}, requirements={"assets"=".+"})
     */
    public function index(ParameterBagInterface $parameterBag, string $assets)
    {
        $file = $parameterBag->get('kernel.project_dir').'/public/build/'.$assets;

        if (!is_file($file)) {
            return $this->json([], 404);
        }

        $stream = new Stream($file);
        $response = new BinaryFileResponse($stream);
        $response->headers->add(['Content-Type' => $this->guessMimeType($file)]);

        return $response;
    }

    private function guessMimeType(string $filename)
    {
        $supportedMime = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
        ];

        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

        return $supportedMime[$fileExtension];
    }
}
