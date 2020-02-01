<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

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

$app->get('/[{route:.+}]', function (Request $request, Response $response, $args) {
    $targetDir = '/' === $_SERVER['REQUEST_URI'] ? '' : urldecode($_SERVER['REQUEST_URI']);

    $entries = preg_grep('/^([^.])/', scandir(__DIR__.$targetDir));
    natsort($entries);

    foreach ($entries as $entry) {
        $uri = $targetDir.'/'.$entry;
        $link = sprintf('<a href="%s">%s</a></br>', $uri, $entry);
        echo $link;
    }

    return $response;
});

$app->run();
