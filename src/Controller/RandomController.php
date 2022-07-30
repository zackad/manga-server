<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Search;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomController extends AbstractController
{
    /**
     * @Route("/random", name="app_random")
     */
    public function randomEntries(Search $search, PaginatorInterface $paginator): Response
    {
        $searchIndex = (array) $search->buildSearchIndex();
        $maximumEntryCount = min(count($searchIndex), 30);
        /** @psalm-var non-empty-array<array-key, array> $searchIndex */
        $randomizedIndex = array_rand($searchIndex, $maximumEntryCount);
        /** @psalm-var array $randomizedIndex */
        $randomEntries = array_intersect_key($searchIndex, array_flip($randomizedIndex));
        shuffle($randomEntries);
        $randomEntries = iterator_to_array($search->populateEntry($randomEntries));

        $pagination = $paginator->paginate([]);
        $pagination->setItems($randomEntries);

        return $this->render('entry_list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
