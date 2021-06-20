<?php

namespace App\Service;

class ComicBook
{
    /**
     * @param string $pathname Pathname of zip file/comicbook
     *
     * @return false|string Resolveable image url, or false on failure
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
            return $images[array_key_first($images)];
        }

        return false;
    }

    private function getImages(\ZipArchive $archive): \Generator
    {
        for ($index = 0; $index < $archive->numFiles; ++$index) {
            $cover = $archive->statIndex($index)['name'];
            $coverPatternExtension = '/.+(jpe?g|png|webp)$/i';
            if (preg_match($coverPatternExtension, $cover)) {
                yield $cover;
            }
        }
    }
}
