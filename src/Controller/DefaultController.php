<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DirectoryListing;
use App\Service\NextChapterResolver;
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
     * @Route("/{path}", name="default", methods={"GET"}, requirements={"path"="^(?!build).+"})
     */
    public function index(DirectoryListing $listing, Request $request, NextChapterResolver $resolver, string $mangaRoot): Response
    {
        $path = $request->attributes->get('path', '');
        $target = sprintf('%s/%s', $mangaRoot, $path);

        $nextPage = '' === $request->query->get('next');

        if ($nextPage) {
            return $this->redirect($resolver->resolve());
        }

        if (is_file($target)) {
            return $this->serveBinaryResponse($target);
        }

        if (!is_dir($target)) {
            throw $this->createNotFoundException(sprintf('Directory "%s" could not be found inside manga root directory.', $path));
        }

        $data = $listing->scan($target, $path);
        $directories = array_filter($data, function ($item) {return 'directory' === $item['type']; });
        $files = array_filter($data, function ($item) {return 'file' === $item['type']; });
        $archives = array_filter($data, function ($item) {return 'archive' === $item['type']; });

        return $this->render('entry_list.html.twig', [
            'entries' => array_merge($directories, $files, $archives),
        ]);
    }

    private function serveBinaryResponse(string $target): Response
    {
        $stream = new Stream($target);
        $response = new BinaryFileResponse($stream);
        $response->setExpires(new \DateTime('+1 week'));

        return $response;
    }
}
