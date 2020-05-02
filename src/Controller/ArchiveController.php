<?php

namespace App\Controller;

use App\Service\ArchiveReader;
use App\Service\DirectoryListing;
use App\Service\PathTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function archiveItem()
    {
        return $this->json([
            'controller_name' => 'ArchiveController',
        ]);
    }
}
