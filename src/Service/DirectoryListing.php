<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Finder\Finder;
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

    public function __construct(CacheInterface $cache, ComicBook $comicBook)
    {
        $this->za = new \ZipArchive();
        $this->finder = new Finder();
        $this->cache = $cache;
        $this->comicBook = $comicBook;
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
            $hasCover = $this->comicBook->getCover($target.'/'.$entry);

            $cover = $hasCover ? rawurlencode($requestUri.'/'.$hasCover) : false;
            yield [
                'uri' => rawurlencode($requestUri),
                'label' => $entry,
                'type' => $this->getType($target.'/'.$entry),
                'cover' => $cover,
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
