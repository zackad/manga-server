<?php

namespace App\Controller;

use App\Service\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", priority=10)
     */
    public function index(Request $request, Search $search)
    {
        $q = $request->query->get('q');

        $results = $search->find($q);

        return $this->render('index.html.twig', [
            'entries' => iterator_to_array($results),
        ]);
    }
}
