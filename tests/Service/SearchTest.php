<?php

namespace App\Tests\Service;

use App\Service\Search;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \App\Service\DirectoryListing
 * @covers \App\Service\Search
 */
class SearchTest extends TestCase
{
    private $search;

    protected function setUp(): void
    {
        $this->search = new Search($_ENV['MANGA_ROOT_DIRECTORY'], $_ENV['MAXIMUM_SEARCH_DEPTH']);
    }

    /**
     * Test search using with simple string.
     */
    public function testSearchWithEmptySringReturnZeroResult()
    {
        $string = '';
        $results = $this->search->find($string);

        $this->assertIsIterable($results);
        $this->assertCount(0, $results);
    }

    public function testResultDataStructure()
    {
        $generator = $this->search->find('archive');
        $result = iterator_to_array($generator)[0];

        $this->assertArrayHasKey('uri', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('type', $result);
        $this->assertArrayHasKey('cover', $result);
    }
}
