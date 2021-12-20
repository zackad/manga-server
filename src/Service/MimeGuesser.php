<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mime\MimeTypes;

class MimeGuesser
{
    public function guessMimeType(string $filename): string
    {
        $mimeTypes = new MimeTypes();
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

        return $mimeTypes->getMimeTypes($fileExtension)[0] ?? 'application/octet-stream';
    }
}
