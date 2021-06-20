<?php

namespace App\Service;

class DirectoryListing
{
    /**
     * @var \ZipArchive
     */
    private $za;

    public function __construct()
    {
        $this->za = new \ZipArchive();
    }

    public function scan(string $target, string $uriPrefix): array
    {
        $entries = preg_grep('/^([^.])/', scandir($target));
        natsort($entries);

        return iterator_to_array($this->buildList($entries, $uriPrefix, $target));
    }

    public function buildList(array $entries, string $uriPrefix, string $target = ''): \Traversable
    {
        $comicBook = new ComicBook();
        foreach ($entries as $entry) {
            $requestUri = $uriPrefix.'/'.$entry;
            $hasCover = $comicBook->getCover($target.'/'.$entry);

            $cover = $hasCover ? rawurlencode($requestUri.'/'.$hasCover) : false;
            yield [
                'uri' => rawurlencode($requestUri),
                'label' => $entry,
                'type' => $this->getType($target.'/'.$entry),
                'cover' => $cover,
            ];
        }
    }

    private function getType(string $pathname): string
    {
        if (is_dir($pathname)) {
            return 'directory';
        }

        try {
            $this->za->open($pathname);

            return 'archive';
        } catch (\Exception $exception) {
        }

        return 'file';
    }
}
