<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Search;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\App\Service\DirectoryListing::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(Search::class)]
class SearchTest extends TestCase
{
    private $search;

    protected function setUp(): void
    {
        $cache = new FilesystemTagAwareAdapter();
        $urlGenerator = $this->createStub(UrlGeneratorInterface::class);
        $this->search = new Search($_ENV['APP_MEDIA_DIRECTORY'], $cache, $urlGenerator);
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
