<?php

namespace App\Controller;

use App\Service\ArchiveReader;
use App\Service\DirectoryListing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function archiveListing(Request $request, DirectoryListing $listing)
    {
        $requestUri = $request->getRequestUri();
        $uriPrefix = '/' === $requestUri ? '' : urldecode($requestUri);

        $target = $_ENV['MANGA_ROOT_DIRECTORY'].$uriPrefix;
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
    public function archiveItem()
    {
        return $this->json([
            'controller_name' => 'ArchiveController',
        ]);
    }
}
