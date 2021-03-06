<?php

namespace App\Tests\Service;

use App\Service\ArchiveReader;
use PHPUnit\Framework\TestCase;

class ArchiveReaderTest extends TestCase
{
    public function testListingArchiveContent()
    {
        $path = dirname(__DIR__).'/stub/archive.zip';
        $za = new ArchiveReader($path);
        $list = $za->getList();
        $this->assertIsIterable($list);
        $this->assertEquals('image.jpeg', $list[0]);
    }
}
