<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Search;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, Search $search, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $response = new Response();
        $errorMessage = null;

        $q = (string) $request->query->get('q', '');
        $results = $search->find($q);
        $entries = iterator_to_array($results);

        if (0 === count($entries)) {
            $response->setStatusCode(404);
            $errorMessage = 'Empty search result, please use other "search term"';
        }

        // disable pagination until I can figure out how to build pagination url with multiple query params
        $pagination = $paginator->paginate($entries, $page);

        return $this->render('entry_list.html.twig', [
            'entries' => $entries,
            'error_message' => $errorMessage,
            'pagination' => $pagination,
        ], $response);
    }
}
