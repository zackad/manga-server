<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class Search
{
    public const SUPPORTED_ARCHIVE_FORMAT = '/.*\.(zip|cbz|epub)$/i';

    /** @var Finder */
    private $finder;
    /** @var ComicBook */
    private $comicBook;
    /** @var string */
    private $mangaRoot;
    /** @var CacheInterface */
    private $cache;
    /** @var UrlGeneratorInterface */
    private $urlGenerator;
    /** @var string */
    private $searchIndexExcluded;

    public function __construct(string $mangaRoot, CacheInterface $cache, ComicBook $comicBook, UrlGeneratorInterface $urlGenerator, string $searchIndexExcluded = '')
    {
        $this->finder = new Finder();
        $this->comicBook = $comicBook;
        $this->mangaRoot = $mangaRoot;
        $this->cache = $cache;
        $this->urlGenerator = $urlGenerator;
        $this->searchIndexExcluded = $searchIndexExcluded;
    }

    public function find(string $search = ''): \Generator
    {
        /*
         * Prevent traversing all valid archive file and force user to include
         * search pattern with atleast 3 characters.
         */
        if (strlen($search) < 3) {
            return;
        }

        $list = $this->buildSearchIndex();
        $list = array_filter((array) $list, function (array $item) use ($search): bool {
            return (bool) preg_match(sprintf('/%s/i', $search), $item['basename']);
        });

        yield from $this->populateEntry($list);
    }

    public function populateEntry(array $list): \Generator
    {
        /** @var array $file */
        foreach ($list as $file) {
            $filename = trim($file['relative_path'], '/');
            $hasCover = $this->comicBook->getCover($file['realpath']);
            $uri = $this->urlGenerator->generate('app_archive_list', ['path' => $filename]);
            $coverUrl = !$hasCover
                ? false
                : $this->urlGenerator->generate('app_archive_item', ['archive_item' => $filename.'/'.$hasCover]);
            yield [
                'uri' => $uri,
                'label' => $file['basename'],
                'type' => 'archive',
                'cover' => $coverUrl,
            ];
        }
    }

    public function buildSearchIndex(): iterable
    {
        return $this->cache->get('search-index', function (ItemInterface $cacheItem) {
            $cacheItem->expiresAfter(86400); // 24 hours
            $patterns = self::SUPPORTED_ARCHIVE_FORMAT;

            $excludedFinderPath = explode(' ', $this->searchIndexExcluded);
            $this->finder
                ->files()
                ->ignoreUnreadableDirs()
                ->followLinks()
                ->in($this->mangaRoot)
                ->exclude($excludedFinderPath)
                ->name($patterns);

            $indexData = [];
            foreach ($this->finder as $item) {
                $indexData[] = [
                    'basename' => $item->getBasename(),
                    'realpath' => $item->getRealPath(),
                    'relative_path' => sprintf('/%s/%s', $item->getRelativePath(), $item->getBasename()),
                ];
            }
            usort($indexData, function ($a, $b) {
                return strnatcmp($a['basename'], $b['basename']);
            });

            return array_values($indexData);
        });
    }
}
