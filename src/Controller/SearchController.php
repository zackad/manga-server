<?php

namespace App\Controller;

use App\Service\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", priority=10)
     */
    public function index(Request $request, Search $search)
    {
        $response = new Response();

        $q = $request->query->get('q') ?? '';
        $results = $search->find($q);
        $entries = iterator_to_array($results);

        if (0 === count($entries)) {
            $response->setStatusCode(404);
        }

        return $this->render('index.html.twig', [
            'entries' => $entries,
        ], $response);
    }
}
