<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__.'/vendor/autoload.php';

$app = AppFactory::create();

$twig = Twig::create('./');

$app->add(TwigMiddleware::create($app, $twig));

$app->get('/[{route:.+}]', function (Request $request, Response $response) {
    $view = Twig::fromRequest($request);
    $targetDir = '/' === $_SERVER['REQUEST_URI'] ? '' : urldecode($_SERVER['REQUEST_URI']);

    $mangaDir = __DIR__.$targetDir;

    if (is_file($mangaDir)) {
        $file = fopen($mangaDir, 'r');
        $streamFile = new Stream($file);

        return $response
            ->withHeader('Content-type', mime_content_type($mangaDir))
            ->withBody($streamFile);
    }

    $entries = preg_grep('/^([^.])/', scandir($mangaDir));
    natsort($entries);

    $data = [];

    foreach ($entries as $entry) {
        $uri = $targetDir.'/'.$entry;
        $data[] = ['uri' => $uri, 'label' => $entry];
    }

    return $view->render($response, 'template.html.twig', [
        'entries' => $data,
    ]);
});

$app->run();
