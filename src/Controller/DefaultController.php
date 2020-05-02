<?php

namespace App\Controller;

use App\Service\DirectoryListing;
use App\Service\PathTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @Route("/{default}", name="default", methods={"GET"}, requirements={"default"="^(?!build).+"})
     */
    public function index(DirectoryListing $listing, PathTool $pathTool)
    {
        $uriPrefix = $pathTool->getPrefix();
        $target = $pathTool->getTarget();

        if (is_file($target)) {
            $stream = new Stream($target);
            $response = new BinaryFileResponse($stream);
            $response->setExpires(new \DateTime('+1 week'));

            return $response;
        }

        if (!is_dir($target)) {
            throw $this->createNotFoundException('Directory not found.');
        }

        $data = $listing->scan($target, $uriPrefix);

        return $this->render('index.html.twig', [
            'entries' => $data,
        ]);
    }
}
