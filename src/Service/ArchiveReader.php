<?php

namespace App\Service;

class ArchiveReader
{
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function getList(): array
    {
        return iterator_to_array($this->generateList());
    }

    private function generateList(): \Traversable
    {
        $za = new \ZipArchive();
        $za->open($this->filename);
        $indexNumber = 0;

        while ($indexNumber < $za->numFiles) {
            yield $za->statIndex($indexNumber)['name'];
            ++$indexNumber;
        }

        $za->close();
    }
}
