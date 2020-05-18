<?php

namespace App\Service;

class ComicBook
{
    /**
     * @param string $pathname Pathname of zip file/comicbook
     *
     * @return string|false Resolveable image url, or false on failure
     */
    public function getCover(string $pathname)
    {
        $za = new \ZipArchive();
        $successOpening = $za->open($pathname);

        if (true !== $successOpening) {
            return false;
        }

        for ($index = 0; $index < $za->numFiles; ++$index) {
            $cover = $za->statIndex($index)['name'];
            $coverPatternExtension = '/.+(jpe?g|png|webp)$/i';
            if (preg_match($coverPatternExtension, $cover)) {
                return $cover;
            }
        }

        return false;
    }
}
