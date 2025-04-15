<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\MimeGuesser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(MimeGuesser::class)]
class MimeGuesserTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('filenameWithValidExtension')]
    public function testGetValidMimeTypes($filename, $mime)
    {
        $guesser = new MimeGuesser();
        $this->assertEquals($mime, $guesser->guessMimeType($filename));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('filenameWithUnusualExtension')]
    public function testGetMimeTypesReturningGenericMimeTypes($filename, $mime)
    {
        $guesser = new MimeGuesser();
        $this->assertEquals($mime, $guesser->guessMimeType($filename));
    }

    public static function filenameWithValidExtension()
    {
        return [
            ['/image.jpeg', 'image/jpeg'],
            ['/image.JPG', 'image/jpeg'],
            ['/image.PNG', 'image/png'],
            ['/image.WEBP', 'image/webp'],
        ];
    }

    public static function filenameWithUnusualExtension()
    {
        return [
            ['file.con', 'application/octet-stream'],
            ['file.sock', 'application/octet-stream'],
            ['file.knot', 'application/octet-stream'],
        ];
    }
}
