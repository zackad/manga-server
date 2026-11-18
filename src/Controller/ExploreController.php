<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DirectoryListing;
use App\Service\NextChapterResolver;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExploreController extends AbstractController
{
    #[Route('/explore', name: 'app_explore', methods: ['GET'])]
    public function explore(
        DirectoryListing $listing,
        Request $request,
        NextChapterResolver $resolver,
        #[Autowire('%env(resolve:APP_MEDIA_DIRECTORY)%')] string $mangaRoot,
        PaginatorInterface $paginator,
    ): Response {
        $path = (string) $request->query->get('path', '/');
        $decodedPath = rawurldecode($path);
        if (preg_match('/\.(cbz|epub|zip)$/i', $decodedPath)) {
            return $this->redirectToRoute('app_archive_list', ['path' => $path]);
        }

        // Make sure that it won't traverse outside APP_MEDIA_DIRECTORY
        $realMediaDirectory = realpath($mangaRoot);
        $target = realpath(sprintf('%s/%s', $realMediaDirectory, $decodedPath));
        if (false === $target || !str_starts_with($target, (string) $realMediaDirectory)) {
            throw $this->createNotFoundException();
        }

        $page = $request->query->getInt('page', 1);

        if (is_file($target)) {
            return $this->serveBinaryResponse($target);
        }

        $entryList = $listing->scan($target);
        $pagination = $paginator->paginate($entryList, $page);
        $populatedList = $listing->buildList($pagination->getItems(), $decodedPath, $target);
        $pagination->setItems(iterator_to_array($populatedList));

        return $this->render('entry_list.html.twig', [
            'pagination' => $pagination,
            'path' => $path,
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
