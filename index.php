<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__.'/vendor/autoload.php';

$MANGA_ROOT_DIRECTORY = getenv('MANGA_ROOT_DIRECTORY') ?: __DIR__;
$supportedMime = [
    'css' => 'text/css',
    'js' => 'application/javascript',
    'json' => 'application/json',
];

$app = AppFactory::create();

$twig = Twig::create(__DIR__);

$app->add(TwigMiddleware::create($app, $twig));

$app->get('/static/[{assets:.+}]', function (Request $request, Response $response) use ($supportedMime) {
    $uri = $request->getUri()->getPath();
    $filename = __DIR__.$uri;

    if (is_file($filename)) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $resource = fopen($filename, 'r');
        $stream = new Stream($resource);

        return $response
            ->withAddedHeader('Content-Type', $supportedMime[$extension])
            ->withAddedHeader('Cache-Control', 'public, max-age=604800')
            ->withBody($stream)
        ;
    }

    return $response->withStatus(404);
});

$app->get('/[{route:.+}]', function (Request $request, Response $response) use ($MANGA_ROOT_DIRECTORY) {
    $view = Twig::fromRequest($request);
    $targetDir = '/' === $_SERVER['REQUEST_URI'] ? '' : urldecode($_SERVER['REQUEST_URI']);

    $mangaDir = $MANGA_ROOT_DIRECTORY.$targetDir;

    if (is_file($mangaDir)) {
        $file = fopen($mangaDir, 'r');
        $streamFile = new Stream($file);

        return $response
            ->withAddedHeader('Cache-Control', 'public, max-age=86400')
            ->withBody($streamFile)
        ;
    }

    if (!is_dir($mangaDir)) {
        return $response->withStatus(404);
    }

    $entries = preg_grep('/^([^.])/', scandir($mangaDir));
    natsort($entries);

    $data = [];

    foreach ($entries as $entry) {
        $uri = $targetDir.'/'.$entry;
        $data[] = ['uri' => $uri, 'label' => $entry, 'isDirectory' => is_dir($mangaDir.'/'.$entry)];
    }

    $manifest = json_decode(file_get_contents('static/manifest.json'), true);

    return $view->render($response, 'template.html.twig', [
        'entries' => $data,
        'manifest' => $manifest,
    ]);
});

$app->run();
