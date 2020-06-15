<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

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
        $finder = new Finder();
        $root = $_ENV['MANGA_ROOT_DIRECTORY'];
        $search = '';
        $patterns = sprintf('/.*%s.*\.(zip|cbz)$/i', $search);
        $finder->files()->depth('< 3')->in($root)->name($patterns);
        $results = iterator_to_array($finder);

        $this->assertTrue($finder->hasResults());
        $this->assertGreaterThanOrEqual(3, count($results));
    }
}
