<?php

namespace App\Tests\Service;

use App\Service\MimeGuesser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \App\Service\MimeGuesser
 */
class MimeGuesserTest extends TestCase
{
    /**
     * @dataProvider filenameWithValidExtension
     *
     * @param mixed $filename
     * @param mixed $mime
     */
    public function testGetValidMimeTypes($filename, $mime)
    {
        $guesser = new MimeGuesser();
        $this->assertEquals($mime, $guesser->guessMimeType($filename));
    }

    /**
     * @dataProvider filenameWithUnusualExtension
     *
     * @param mixed $filename
     * @param mixed $mime
     */
    public function testGetMimeTypesReturningGenericMimeTypes($filename, $mime)
    {
        $guesser = new MimeGuesser();
        $this->assertEquals($mime, $guesser->guessMimeType($filename));
    }

    public function filenameWithValidExtension()
    {
        return [
            ['/image.jpeg', 'image/jpeg'],
            ['/image.JPG', 'image/jpeg'],
            ['/image.PNG', 'image/png'],
            ['/image.WEBP', 'image/webp'],
        ];
    }

    public function filenameWithUnusualExtension()
    {
        return [
            ['file.con', 'application/octet-stream'],
            ['file.sock', 'application/octet-stream'],
            ['file.knot', 'application/octet-stream'],
        ];
    }
}
