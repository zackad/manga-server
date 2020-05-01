<?php

namespace App\Service;

class ArchiveReader
{
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function getList(): iterable
    {
        $za = new \ZipArchive();
        $za->open($this->filename);
        $list = [];
        $indexNumber = 0;

        while ($indexNumber < $za->numFiles) {
            $list[] = $za->statIndex($indexNumber)['name'];
            ++$indexNumber;
        }

        $za->close();

        return $list;
    }
}
