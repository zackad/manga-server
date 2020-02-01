<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
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

$app = AppFactory::create();

$twig = Twig::create('./');

$app->add(TwigMiddleware::create($app, $twig));

$app->get('/favicon.ico', function (Request $request, Response $response, $args) {
    return $response->withStatus(404);
});

$app->get('/[{route:.+}]', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    $targetDir = '/' === $_SERVER['REQUEST_URI'] ? '' : urldecode($_SERVER['REQUEST_URI']);

    $entries = preg_grep('/^([^.])/', scandir(__DIR__.$targetDir));
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
