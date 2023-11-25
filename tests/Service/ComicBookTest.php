<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\ComicBook;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;

/**
 * @internal
 *
 * @covers \App\Service\ComicBook
 * @covers \App\Service\DirectoryListing
 */
class ComicBookTest extends TestCase
{
    private $comicBook;

    protected function setUp(): void
    {
        $cache = new FilesystemTagAwareAdapter();
        $cache->clear();
        $this->comicBook = new ComicBook($cache);
    }

    public function testGetCover()
    {
        $testFile = dirname(__DIR__).'/stub/archive.zip';

        $this->assertEquals('image.jpeg', $this->comicBook->getCover($testFile));
    }

    public function testGetCoverWithFailure()
    {
        $this->assertFalse($this->comicBook->getCover('not-existing.zip'));
    }
}
