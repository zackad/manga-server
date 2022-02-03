<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ArchiveReader;
use App\Service\DirectoryListing;
use App\Service\MimeGuesser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArchiveController extends AbstractController
{
    /**
     * @Route(
     *     "/{path}",
     *     name="archive_list",
     *     methods={"GET"},
     *     requirements={"path"=".+(\.zip|cbz)$"}
     * )
     */
    public function archiveListing(Request $request, DirectoryListing $listing, string $mangaRoot): Response
    {
        $path = $request->attributes->get('path');
        $target = sprintf('%s/%s', $mangaRoot, $path);

        $entries = new ArchiveReader($target);
        $entryList = iterator_to_array($listing->buildList($entries->getList(), $path, $target));

        return $this->render('entry_list.html.twig', [
            'entries' => $entryList,
        ]);
    }

    /**
     * @Route(
     *     "/{archive_item}",
     *     name="archive_item",
     *     methods={"GET"},
     *     requirements={"archive_item"=".+(\.zip|cbz\/).+$"}
     * )
     */
    public function archiveItem(Request $request, MimeGuesser $guesser, string $mangaRoot): Response
    {
        $path = $request->attributes->get('archive_item');
        $target = sprintf('%s/%s', $mangaRoot, $path);
        $archivePath = preg_replace('/(?<=cbz|zip).*$/i', '', $target);
        $entryName = preg_replace('/.*(cbz|zip)\//i', '', $target);

        $za = new \ZipArchive();
        $za->open($archivePath);
        $inputStream = $za->getStream($entryName);
        if (false === $inputStream) {
            throw $this->createNotFoundException();
        }

        $response = new StreamedResponse(function () use ($inputStream) {
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
