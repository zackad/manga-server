<?php

namespace App\Service;

class DirectoryListing
{
    private $za;

    public function __construct()
    {
        $this->za = new \ZipArchive();
    }

    public function scan(string $target, string $uriPrefix): iterable
    {
        $entries = preg_grep('/^([^.])/', scandir($target));
        natsort($entries);

        return $this->buildList($entries, $uriPrefix, $target);
    }

    public function buildList(array $entries, string $uriPrefix, string $target = ''): iterable
    {
        $comicBook = new ComicBook();
        $data = [];
        foreach ($entries as $entry) {
            $requestUri = $uriPrefix.'/'.$entry;
            $hasCover = $comicBook->getCover($target.'/'.$entry);

            $cover = $hasCover ? rawurlencode($requestUri.'/'.$hasCover) : false;
            $data[] = [
                'uri' => rawurlencode($requestUri),
                'label' => $entry,
                'type' => $this->getType($target.'/'.$entry),
                'cover' => $cover,
            ];
        }

        return $data;
    }

    private function getType(string $pathname): string
    {
        if (is_dir($pathname)) {
            return 'directory';
        }

        if (true === $this->za->open($pathname)) {
            return 'archive';
        }

        return 'file';
    }
}
