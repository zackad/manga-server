<?php

declare(strict_types=1);

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
        $list = iterator_to_array($this->generateList());
        natsort($list);

        return $list;
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
