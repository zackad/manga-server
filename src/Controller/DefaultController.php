<?php

namespace App\Controller;

use App\Service\DirectoryListing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @Route("/{default}", name="default", methods={"GET"}, requirements={"default"="^(?!build).+"})
     */
    public function index(Request $request, DirectoryListing $listing)
    {
        $requestUri = $request->getRequestUri();
        $targetDir = '/' === $requestUri ? '' : urldecode($requestUri);

        $mangaDir = $_ENV['MANGA_ROOT_DIRECTORY'].$targetDir;

        if (is_file($mangaDir)) {
            $stream = new Stream($mangaDir);
            $response = new BinaryFileResponse($stream);
            $response->setExpires(new \DateTime('+1 week'));

            return $response;
        }

        if (!is_dir($mangaDir)) {
            throw $this->createNotFoundException('Directory not found.');
        }

        $data = $listing->scan($mangaDir, $targetDir);

        return $this->render('index.html.twig', [
            'entries' => $data,
        ]);
    }
}
