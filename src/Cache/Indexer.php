<?php

declare(strict_types=1);

namespace App\Cache;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Cache\ItemInterface;

class Indexer
{
    final public const CACHE_KEY = 'search-index';
    final public const CACHE_TTL = 10_800; // 3 hours
    final public const SUPPORTED_ARCHIVE_FORMAT = '/.*\.(zip|cbz|epub)$/i';

    private Finder $finder;

    public function __construct(
        #[Autowire('%env(resolve:APP_MEDIA_DIRECTORY)%')]
        private readonly string $mangaRoot,
        #[Autowire('%env(SEARCH_INDEX_EXCLUDED)%')]
        private readonly string $searchIndexExcluded = '',
    ) {
        $this->finder = new Finder();
    }

    /**
     * @return iterable<array<string, string>>
     */
    public function buildIndex(ItemInterface $item): iterable
    {
        $item->expiresAfter(self::CACHE_TTL);

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

        return $indexData;
    }
}
