<?php

namespace App\Service;

use Symfony\Component\Finder\Finder;

class Search
{
    private $finder;
    private $comicBook;
    private $mangaRootDirectory;

    public function __construct()
    {
        $this->finder = new Finder();
        $this->comicBook = new ComicBook();
        $this->mangaRootDirectory = $_ENV['MANGA_ROOT_DIRECTORY'];
    }

    public function find(string $search = ''): \Generator
    {
        /*
         * Prevent traversing all valid archive file and force user to include
         * search pattern.
         */
        if ('' === $search) {
            return;
        }

        $patterns = sprintf('/.*%s.*\.(zip|cbz)$/i', $search);
        $this->finder
            ->files()
            ->depth(sprintf('< %s', $_ENV['MAXIMUM_SEARCH_DEPTH']))
            ->ignoreUnreadableDirs()
            ->followLinks()
            ->in($this->mangaRootDirectory)
            ->name($patterns)
            ->sortByName(true)
        ;

        foreach ($this->finder as $file) {
            $filename = $file->getRelativePathname();
            $hasCover = $this->comicBook->getCover($file->getRealPath());
            $coverUrl = $hasCover ? rawurlencode(sprintf('%s/%s', $filename, $hasCover)) : false;
            yield [
                'uri' => rawurlencode($file->getRelativePathname()),
                'label' => $file->getFilenameWithoutExtension(),
                'type' => 'archive',
                'cover' => $coverUrl,
            ];
        }
    }
}