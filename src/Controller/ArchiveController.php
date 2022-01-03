<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ArchiveReader;
use App\Service\DirectoryListing;
use App\Service\MimeGuesser;
use App\Service\PathTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArchiveController extends AbstractController
{
    /**
     * @Route(
     *     "/{archive_list}",
     *     name="archive_list",
     *     methods={"GET"},
     *     requirements={"archive_list"=".+(\.zip|cbz)$"}
     * )
     */
    public function archiveListing(DirectoryListing $listing, PathTool $pathTool): Response
    {
        $uriPrefix = $pathTool->getPrefix();
        $target = rawurldecode($pathTool->getTarget());

        $entries = new ArchiveReader($target);
        $entryList = iterator_to_array($listing->buildList($entries->getList(), $uriPrefix, $target));

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
    public function archiveItem(PathTool $pathTool, MimeGuesser $guesser): Response
    {
        $target = $pathTool->getTarget();
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
