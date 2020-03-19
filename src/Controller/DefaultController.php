<?php

namespace App\Controller;

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
    public function index()
    {
        $targetDir = '/' === $_SERVER['REQUEST_URI'] ? '' : urldecode($_SERVER['REQUEST_URI']);

        $mangaDir = $_ENV['MANGA_ROOT_DIRECTORY'].$targetDir;

        if (is_file($mangaDir)) {
            $stream = new Stream($mangaDir);
            $response = new BinaryFileResponse($stream);
            $response->setExpires(new \DateTime('+1 day'));

            return $response;
        }

        if (!is_dir($mangaDir)) {
            return $this->json(['status' => 'ERROR', 'message' => 'Not Found!'], 404);
        }

        $entries = preg_grep('/^([^.])/', scandir($mangaDir));
        natsort($entries);

        $data = [];

        foreach ($entries as $entry) {
            $uri = $targetDir.'/'.$entry;
            $data[] = ['uri' => $uri, 'label' => $entry, 'isDirectory' => is_dir($mangaDir.'/'.$entry)];
        }

        return $this->render('template.html.twig', [
            'entries' => $data,
        ]);
    }
}
