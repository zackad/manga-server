<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DirectoryListing;
use App\Service\NextChapterResolver;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/explore", name="app_explore", methods={"GET"})
     */
    public function index(DirectoryListing $listing, Request $request, NextChapterResolver $resolver, string $mangaRoot, PaginatorInterface $paginator): Response
    {
        if ('app_home' === $request->attributes->get('_route')) {
            return $this->redirectToRoute('app_explore');
        }

        $path = (string) $request->query->get('path', '/');
        $decodedPath = rawurldecode($path);
        if (str_ends_with($decodedPath, '.zip')) {
            return $this->redirectToRoute('app_archive_list', ['path' => $path]);
        }
        $target = sprintf('%s/%s', $mangaRoot, $decodedPath);
        $page = $request->query->getInt('page', 1);

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

        $entryList = $listing->scan($target);
        $pagination = $paginator->paginate($entryList, $page);
        $populatedList = $listing->buildList($pagination->getItems(), $decodedPath, $target);
        $pagination->setItems(iterator_to_array($populatedList));

        return $this->render('entry_list.html.twig', [
            'pagination' => $pagination,
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
