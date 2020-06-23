<?php

namespace App\Tests\Service;

use App\Service\Search;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    /**
     * Test search using with simple string.
     */
    public function testSearchWithEmptySringReturnZeroResult()
    {
        $search = new Search();
        $string = '';
        $results = $search->find($string);

        $this->assertIsIterable($results);
        $this->assertCount(0, $results);
    }

    public function testResultDataStructure()
    {
        $generator = (new Search())->find('archive');
        $result = iterator_to_array($generator)[0];

        $this->assertArrayHasKey('uri', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('type', $result);
        $this->assertArrayHasKey('cover', $result);
    }
}
