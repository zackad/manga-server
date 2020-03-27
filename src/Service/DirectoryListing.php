<?php

namespace App\Service;

class DirectoryListing
{
    public function scan(string $target, string $uriPrefix): iterable
    {
        $entries = preg_grep('/^([^.])/', scandir($target));
        natsort($entries);

        $data = [];

        foreach ($entries as $entry) {
            $requestUri = $uriPrefix.'/'.$entry;
            $data[] = ['uri' => $requestUri, 'label' => $entry, 'isDirectory' => is_dir($target.'/'.$entry)];
        }

        return $data;
    }
}
