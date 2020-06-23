<?php

namespace App\Tests\Service;

use App\Service\ComicBook;
use PHPUnit\Framework\TestCase;

class ComicBookTest extends TestCase
{
    public function testGetCover()
    {
        $testFile = dirname(__DIR__).'/stub/archive.zip';
        $comicBook = new ComicBook();

        $this->assertEquals('image.jpeg', $comicBook->getCover($testFile));
    }

    public function testGetCoverWithFailure()
    {
        $comicBook = new ComicBook();
        $this->assertFalse($comicBook->getCover('not-existing.zip'));
    }
}
