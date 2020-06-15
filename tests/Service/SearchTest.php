<?php

namespace App\Tests\Service;

use App\Service\Search;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class SearchTest extends TestCase
{
    /**
     * Test search using with simple string.
     */
    public function testGenericSearch()
    {
        $search = new Search();
        $string = '';
        $results = $search->find($string);

        $this->assertIsIterable($results);
        $this->assertGreaterThanOrEqual(3, count($results));
    }
}
