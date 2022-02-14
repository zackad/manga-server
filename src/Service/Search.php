<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class Search
{
    /** @var Finder */
    private $finder;
    /** @var ComicBook */
    private $comicBook;
    /** @var string */
    private $mangaRoot;
    /** @var CacheInterface */
    private $cache;
    /** @var string */
    private $searchIndexExcluded;

    public function __construct(string $mangaRoot, CacheInterface $cache, ComicBook $comicBook, string $searchIndexExcluded = '')
    {
        $this->finder = new Finder();
        $this->comicBook = $comicBook;
        $this->mangaRoot = $mangaRoot;
        $this->cache = $cache;
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
            $filename = $file['relative_path'];
            $hasCover = $this->comicBook->getCover($file['realpath']);
            $coverUrl = $hasCover ? rawurlencode(sprintf('%s/%s', $filename, $hasCover)) : false;
            yield [
                'uri' => rawurlencode($file['relative_path']),
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
            $patterns = '/.*\.(zip|cbz)$/i';

            $excludedFinderPath = explode(' ', $this->searchIndexExcluded);
            $this->finder
                ->files()
                ->ignoreUnreadableDirs()
                ->followLinks()
                ->in($this->mangaRoot)
                ->exclude($excludedFinderPath)
                ->name($patterns)
                ->sortByName(true);

            $list = iterator_to_array($this->finder);
            $mappedArray = array_map(
                function (SplFileInfo $item) {
                    return [
                        'basename' => $item->getBasename(),
                        'realpath' => $item->getRealPath(),
                        'relative_path' => sprintf('/%s/%s', $item->getRelativePath(), $item->getBasename()),
                    ];
                }, $list);

            return array_values($mappedArray);
        });
    }
}
