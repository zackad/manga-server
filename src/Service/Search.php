<?php

declare(strict_types=1);

namespace App\Service;

use App\Cache\Indexer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\CacheInterface;

class Search
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly Indexer $indexer,
    ) {
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
        return $this->cache->get('search-index', [$this->indexer, 'buildIndex']);
    }
}
