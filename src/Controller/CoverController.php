<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ComicBook;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CoverController extends AbstractController
{
    public function __construct(private readonly TagAwareCacheInterface $cache)
    {
    }

    #[Route('/cover', name: 'app_cover_thumbnail')]
    public function thumbnail(string $mangaRoot, Request $request, ComicBook $comicBook): Response
    {
        $filename = (string) $request->query->get('filename');
        $size = $request->query->getInt('size', 512);
        $target = $mangaRoot.'/'.$filename;
        $cacheKey = sprintf('cover-thumbnail-%s-%s', $size, md5($filename));

        $image = $this->cache->get($cacheKey, function (ItemInterface $item) use ($target, $size, $comicBook) {
            $item->tag(['cover', 'thumbnail']);

            $zipArchive = new \ZipArchive();
            $entryName = $comicBook->getCover($target);
            if (!is_string($entryName)) {
                $item->expiresAfter(-1);
                throw new \InvalidArgumentException(sprintf('Cannot retrieve cover from "%s".', $target));
            }
            $zipArchive->open($target);
            /** @var resource $stream */
            $stream = $zipArchive->getStream($entryName);

            $imagine = new Imagine();
            $size = new Box($size, $size);

            return $imagine->read($stream)
                 ->thumbnail($size, ManipulatorInterface::THUMBNAIL_OUTBOUND)
                 ->get('png');
        });

        return new Response($image, headers: ['Content-Type' => 'image/png']);
    }
}
