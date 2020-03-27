<?php

namespace App\Service;

class DirectoryListing
{
    public function scan(string $path, string $targetDir): iterable
    {
        $entries = preg_grep('/^([^.])/', scandir($path));
        natsort($entries);

        $data = [];

        foreach ($entries as $entry) {
            $requestUri = $targetDir.'/'.$entry;
            $data[] = ['uri' => $requestUri, 'label' => $entry, 'isDirectory' => is_dir($path.'/'.$entry)];
        }

        return $data;
    }
}
