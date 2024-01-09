<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ArchiveReader;
use App\Service\DirectoryListing;
use App\Service\MimeGuesser;
use App\Service\NextChapterResolver;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

class ArchiveController extends AbstractController
{
    public function __construct(private readonly string $mangaRoot)
    {
    }

    #[Route(
        '/archive/{path}',
        name: 'app_archive_list',
        requirements: ['path' => '.+\.(zip|cbz|epub)$'], methods: ['GET'])
    ]
    public function archiveListing(
        Request $request,
        DirectoryListing $listing,
        PaginatorInterface $paginator,
        NextChapterResolver $resolver,
    ): Response {
        $page = $request->query->getInt('page', 1);
        $path = $request->attributes->get('path');
        $routeName = $request->get('_route');
        $decodedPath = rawurldecode((string) $path);
        $nextUrl = $resolver->nextUrl($routeName, $decodedPath);
        $prevUrl = $resolver->prevUrl($routeName, $decodedPath);

        $target = sprintf('%s/%s', $this->mangaRoot, $decodedPath);
        $entries = new ArchiveReader($target);
        $listIterator = $listing->buildList($entries->getList(), $decodedPath, $target, true);
        $entryList = iterator_to_array($listIterator);
        $pagination = $paginator->paginate($entryList, $page);

        return $this->render('entry_list.html.twig', [
            'entries' => $entryList,
            'pagination' => $pagination,
            'next_url' => $nextUrl,
            'prev_url' => $prevUrl,
        ]);
    }

    #[Route(
        '/archive/{archive_item}',
        name: 'app_archive_item',
        requirements: ['archive_item' => '.+\.(zip|cbz|epub\/).+$'])
    ]
    public function archiveItem(Request $request, MimeGuesser $guesser): Response
    {
        $path = $request->attributes->get('archive_item');
        $target = sprintf('%s/%s', $this->mangaRoot, $path);
        $archivePath = (string) preg_replace('/(?<=\.cbz|\.epub|\.zip).*$/i', '', $target);
        $archivePath = realpath(rawurldecode($archivePath));
        $entryName = preg_replace('/.*(cbz|epub|zip)\//i', '', $target);

        $za = new \ZipArchive();
        $za->open($archivePath);
        $inputStream = $za->getStream($entryName);
        if (false === $inputStream) {
            throw $this->createNotFoundException();
        }

        $response = new StreamedResponse(function () use ($inputStream) {
            /** @var resource $outputStream */
            $outputStream = fopen('php://output', 'wb');

            stream_copy_to_stream($inputStream, $outputStream);
        });

        $headers = [
            'Content-Type' => $guesser->guessMimeType($entryName),
        ];
        $response->headers->add($headers);
        $response->setExpires(new \DateTime('+1 week'));
        $response->send();

        return $response;
    }
}
