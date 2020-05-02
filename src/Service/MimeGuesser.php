<?php

namespace App\Service;

class MimeGuesser
{
    public static function guessMimeType(string $filename)
    {
        $supportedMime = [
            'css' => 'text/css',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'js' => 'application/javascript',
            'json' => 'application/json',
        ];

        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

        return $supportedMime[$fileExtension];
    }
}
