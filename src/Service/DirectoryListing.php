<?php

namespace App\Service;

class DirectoryListing
{
    public function scan(string $target, string $uriPrefix): iterable
    {
        $entries = preg_grep('/^([^.])/', scandir($target));
        natsort($entries);

        return $this->buildList($entries, $uriPrefix, $target);
    }

    public function buildList(array $entries, string $uriPrefix, string $target = ''): iterable
    {
        $data = [];
        foreach ($entries as $entry) {
            $requestUri = $uriPrefix.'/'.$entry;
            $data[] = ['uri' => urlencode($requestUri), 'label' => $entry, 'isDirectory' => is_dir($target.'/'.$entry)];
        }

        return $data;
    }
}
