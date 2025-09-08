<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Search;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(\App\Service\DirectoryListing::class)]
#[CoversClass(Search::class)]
class SearchTest extends KernelTestCase
{
    private Search $search;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->search = self::getContainer()->get(Search::class);
    }

    /**
     * Test search using with simple string.
     */
    public function testSearchWithEmptySringReturnZeroResult()
    {
        $string = '';
        $results = iterator_to_array($this->search->find($string));

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
