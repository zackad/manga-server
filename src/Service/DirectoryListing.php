<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;

class DirectoryListing
{
    private readonly \ZipArchive $za;
    private readonly Finder $finder;

    public function __construct(private readonly CacheInterface $cache, private readonly UrlGeneratorInterface $urlGenerator)
    {
        $this->za = new \ZipArchive();
        $this->finder = new Finder();
    }

    public function scan(string $target): array
    {
        return $this->cache->get('scan-'.md5($target), function (CacheItemInterface $cacheItem) use ($target) {
            $cacheItem->expiresAfter(600);
            $this->finder
                ->in($target)
                ->depth(0)
                ->sortByName(true);
            $directories = $files = [];
            foreach ($this->finder as $item) {
                if ($item->isDir()) {
                    $directories[] = $item;
                } else {
                    $files[] = $item;
                }
            }
            $list = array_merge($directories, $files);

            return array_values(array_map(fn ($item) => $item->getBaseName(), $list));
        });
    }

    public function buildList(iterable $entries, string $uriPrefix, string $target = '', bool $isArchive = false): \Traversable
    {
        /** @var string $entry */
        foreach ($entries as $entry) {
            $requestUri = rawurlencode(trim($uriPrefix.'/'.$entry, '/'));
            $pathname = $target.'/'.$entry;
            $coverUrl = 'archive' !== $this->getType($pathname) ? false : $this->urlGenerator->generate('app_cover_thumbnail', ['filename' => $requestUri]);

            $uri = !$isArchive
                ? $this->urlGenerator->generate('app_explore', ['path' => $requestUri])
                : $this->urlGenerator->generate('app_archive_item', ['archive_item' => $requestUri]);
            yield [
                'uri' => $uri,
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

        if (!is_readable($pathname)) {
            return 'file';
        }

        $isArchive = $this->za->open($pathname);
        if (true === $isArchive) {
            return 'archive';
        }

        return 'file';
    }
}
