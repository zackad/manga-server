<?php

namespace App\Controller;

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
    public function archiveListing()
    {
        return $this->json([
            'controller_name' => 'ArchiveController',
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
