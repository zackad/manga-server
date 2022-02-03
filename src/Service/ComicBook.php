<?php

declare(strict_types=1);

namespace App\Service;

class ComicBook
{
    public const IMAGE_EXTENSIONS = '/.+(jpe?g|png|webp)$/i';

    /**
     * @param string $pathname Pathname of zip file/comicbook
     *
     * @return false|string Resolvable image url, or false on failure
     */
    public function getCover(string $pathname)
    {
        try {
            $za = new \ZipArchive();
            $za->open($pathname);
        } catch (\Exception $exception) {
            return false;
        }

        $images = iterator_to_array($this->getImages($za));
        natsort($images);

        if (count($images) > 0) {
            return (string) $images[array_key_first($images)];
        }

        return false;
    }

    private function getImages(\ZipArchive $archive): \Generator
    {
        for ($index = 0; $index < $archive->numFiles; ++$index) {
            $cover = (string) $archive->statIndex($index)['name'];
            if (preg_match(self::IMAGE_EXTENSIONS, $cover)) {
                yield $cover;
            }
        }
    }
}
