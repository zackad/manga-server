<?php

namespace App\Service;

use Symfony\Component\Finder\Finder;

class Search
{
    private $finder;
    private $mangaRootDirectory;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
        $this->mangaRootDirectory = $_ENV['MANGA_ROOT_DIRECTORY'];
    }

    public function find(string $search = ''): iterable
    {
        $patterns = sprintf('/.*%s.*\.(zip|cbz)$/i', $search);
        $this->finder
            ->files()
            ->depth('< 3')
            ->ignoreUnreadableDirs()
            ->in($this->mangaRootDirectory)
            ->name($patterns)
            ->sortByName(true)
        ;

        return iterator_to_array($this->finder);
    }
}
