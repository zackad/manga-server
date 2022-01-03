<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DirectoryListing;
use App\Service\NextChapterResolver;
use App\Service\PathTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     * @Route("/{default}", name="default", methods={"GET"}, requirements={"default"="^(?!build).+"})
     */
    public function index(DirectoryListing $listing, PathTool $pathTool, Request $request, NextChapterResolver $resolver): Response
    {
        $uriPrefix = $pathTool->getPrefix();
        $target = $pathTool->getTarget();

        $nextPage = '' === $request->query->get('next');

        if ($nextPage) {
            return $this->redirect($resolver->resolve());
        }

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
        $directories = array_filter($data, function ($item) {return 'directory' === $item['type']; });
        $files = array_filter($data, function ($item) {return 'file' === $item['type']; });
        $archives = array_filter($data, function ($item) {return 'archive' === $item['type']; });

        return $this->render('entry_list.html.twig', [
            'entries' => array_merge($directories, $files, $archives),
        ]);
    }
}
