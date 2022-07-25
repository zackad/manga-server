<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;

class DirectoryListing
{
    /** @var \ZipArchive */
    private $za;
    /** @var Finder */
    private $finder;
    /** @var CacheInterface */
    private $cache;
    /** @var ComicBook */
    private $comicBook;
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(CacheInterface $cache, ComicBook $comicBook, UrlGeneratorInterface $urlGenerator)
    {
        $this->za = new \ZipArchive();
        $this->finder = new Finder();
        $this->cache = $cache;
        $this->comicBook = $comicBook;
        $this->urlGenerator = $urlGenerator;
    }

    public function scan(string $target): array
    {
        return $this->cache->get('scan-'.md5($target), function (CacheItemInterface $cacheItem) use ($target) {
            $cacheItem->expiresAfter(600);
            $this->finder
                ->in($target)
                ->depth(0)
                ->sortByName(true);
            $list = iterator_to_array($this->finder);

            return array_values(array_map(function ($item) {return $item->getBaseName(); }, $list));
        });
    }

    public function buildList(iterable $entries, string $uriPrefix, string $target = ''): \Traversable
    {
        /** @var string $entry */
        foreach ($entries as $entry) {
            $requestUri = $uriPrefix.'/'.$entry;
            $pathname = $target.'/'.$entry;
            $hasCover = $this->comicBook->getCover($pathname);
            $coverUrl = !$hasCover
                ? false
                : $this->urlGenerator->generate('app_archive_item', ['archive_item' => rawurlencode($requestUri.'/'.$hasCover)]);

            yield [
                'uri' => $this->urlGenerator->generate('app_explore', ['path' => rawurlencode($requestUri)]),
                'label' => $entry,
                'type' => $this->getType($pathname),
                'cover' => $coverUrl,
            ];
        }
    }

    private function getType(string $pathname): string
    {
        if (is_dir($pathname)) {
            return 'directory';
        }

        try {
            $isArchive = $this->za->open($pathname);
        } catch (\Exception $exception) {
            return 'file';
        }

        if (true === $isArchive) {
            return 'archive';
        }

        return 'file';
    }
}
