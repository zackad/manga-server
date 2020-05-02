<?php

namespace App\Controller;

use App\Service\MimeGuesser;
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

        if (!file_exists($file)) {
            throw  $this->createNotFoundException(sprintf('File "%s" not found.', $file));
        }

        $stream = new Stream($file);
        $response = new BinaryFileResponse($stream);
        $response->headers->add(['Content-Type' => MimeGuesser::guessMimeType($file)]);
        $response->setExpires(new \DateTime('+1 week'));

        return $response;
    }

}
