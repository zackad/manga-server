<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ComicBook
{
    public const IMAGE_EXTENSIONS = '/.+(jpe?g|png|webp)$/i';

    /** @var TagAwareCacheInterface */
    private $cache;

    public function __construct(TagAwareCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $pathname Pathname of zip file/comicbook
     *
     * @return false|string Resolvable image url, or false on failure
     */
    public function getCover(string $pathname)
    {
        return $this->cache->get('cover-'.md5($pathname), function (ItemInterface $cacheItem) use ($pathname) {
            $cacheItem->tag('cover');
            try {
                $za = new \ZipArchive();
                $za->open($pathname);
            } catch (\Exception $exception) {
                $cacheItem->expiresAfter(-1);

                return false;
            }

            $images = iterator_to_array($this->getImages($za));
            natsort($images);

            if (count($images) > 0) {
                return (string) $images[array_key_first($images)];
            }

            return false;
        });
    }

    private function getImages(\ZipArchive $archive): \Generator
    {
        for ($index = 0; $index < $archive->numFiles; ++$index) {
            $cover = $archive->statIndex($index)['name'] ?? '';
            if (preg_match(self::IMAGE_EXTENSIONS, $cover)) {
                yield $cover;
            }
        }
    }
}
