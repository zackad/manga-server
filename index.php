<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = urldecode(__DIR__.$url['path']);
    if (is_file($file)) {
        return false;
    }
}

require __DIR__.'/vendor/autoload.php';

$MANGA_ROOT_DIRECTORY = getenv('MANGA_ROOT_DIRECTORY') ?? __DIR__;

$app = AppFactory::create();

$twig = Twig::create('./');

$app->add(TwigMiddleware::create($app, $twig));

$app->get('/[{route:.+}]', function (Request $request, Response $response) use ($MANGA_ROOT_DIRECTORY) {
    $view = Twig::fromRequest($request);
    $targetDir = '/' === $_SERVER['REQUEST_URI'] ? '' : urldecode($_SERVER['REQUEST_URI']);

    $mangaDir = $MANGA_ROOT_DIRECTORY.$targetDir;

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
        $data[] = ['uri' => $uri, 'label' => $entry, 'isDirectory' => is_dir($mangaDir.'/'.$entry)];
    }

    return $view->render($response, 'template.html.twig', [
        'entries' => $data,
    ]);
});

$app->run();
