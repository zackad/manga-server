<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class Search
{
    final public const SUPPORTED_ARCHIVE_FORMAT = '/.*\.(zip|cbz|epub)$/i';

    private readonly Finder $finder;

    public function __construct(
        private readonly string $mangaRoot,
        private readonly CacheInterface $cache,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly string $searchIndexExcluded = '',
    ) {
        $this->finder = new Finder();
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
        $list = array_filter((array) $list, fn (array $item): bool => (bool) preg_match(sprintf('/%s/i', $search), (string) $item['basename']));

        yield from $this->populateEntry($list);
    }

    public function populateEntry(array $list): \Generator
    {
        /** @var array $file */
        foreach ($list as $file) {
            $filename = rawurlencode(trim((string) $file['relative_path'], '/'));
            $uri = $this->urlGenerator->generate('app_archive_list', ['path' => $filename]);
            $coverUrl = $this->urlGenerator->generate('app_cover_thumbnail', ['filename' => $filename]);
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
            usort($indexData, fn ($a, $b) => strnatcmp((string) $a['basename'], (string) $b['basename']));

            return array_values($indexData);
        });
    }
}
