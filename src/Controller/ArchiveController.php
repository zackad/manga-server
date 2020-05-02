<?php

namespace App\Controller;

use App\Service\ArchiveReader;
use App\Service\DirectoryListing;
use App\Service\MimeGuesser;
use App\Service\PathTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArchiveController extends AbstractController
{
    /**
     * @Route(
     *     "/{archive_list}",
     *     name="archive_list",
     *     methods={"GET"},
     *     requirements={"archive_list"=".+(\.zip)$"}
     * )
     */
    public function archiveListing(DirectoryListing $listing, PathTool $pathTool)
    {
        $uriPrefix = $pathTool->getPrefix();
        $target = $pathTool->getTarget();

        $entries = new ArchiveReader($target);
        $entryList = $listing->buildList($entries->getList(), $uriPrefix, $target);

        return $this->render('index.html.twig', [
            'entries' => $entryList,
        ]);
    }

    /**
     * @Route(
     *     "/{archive_item}",
     *     name="archive_item",
     *     methods={"GET"},
     *     requirements={"archive_item"=".+(\.zip\/).+$"}
     * )
     */
    public function archiveItem(PathTool $pathTool)
    {
        $archivePath = dirname($pathTool->getTarget());
        $entryName = pathinfo($pathTool->getTarget(), PATHINFO_BASENAME);

        $za = new \ZipArchive();
        $za->open($archivePath);

        $response = new StreamedResponse(function () use ($za, $entryName) {
            $outputStream = fopen('php://output', 'wb');
            $inputStream = $za->getStream($entryName);

            stream_copy_to_stream($inputStream, $outputStream);
        });

        $headers = [
            'Content-Type' => MimeGuesser::guessMimeType($entryName),
        ];
        $response->headers->add($headers);

        return $response;
    }
}
