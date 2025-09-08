<?php

declare(strict_types=1);

namespace App\Cache;

use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Cache\ItemInterface;

class Indexer
{
    final public const SUPPORTED_ARCHIVE_FORMAT = '/.*\.(zip|cbz|epub)$/i';

    private Finder $finder;

    public function __construct(
        private readonly string $mangaRoot,
        private readonly string $searchIndexExcluded = '',
    ) {
        $this->finder = new Finder();
    }

    /**
     * @return iterable<array<string, string>>
     */
    public function buildIndex(ItemInterface $item): iterable
    {
        $item->expiresAfter(86400); // 24 hours

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
