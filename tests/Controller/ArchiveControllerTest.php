<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\App\Controller\ArchiveController::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\App\Service\ArchiveReader::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\App\Service\ComicBook::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\App\Service\DirectoryListing::class)]
class ArchiveControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testCanListArchiveContent()
    {
        $this->client->request('GET', '/archive/archive.zip');
        $this->assertResponseIsSuccessful();
    }

    public function testLoadImageFromArchive()
    {
        $imageInsideArchvie = rawurlencode('archive.zip/image.jpeg');
        ob_start();
        $this->client->request('GET', '/archive/'.$imageInsideArchvie);
        ob_get_clean();
        $expiresExpected = (new \DateTime('+1 week'))->getTimestamp();
        $actualExpires = (new \DateTime($this->client->getResponse()->headers->get('Expires')))->getTimestamp();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'image/jpeg');
        $this->assertLessThanOrEqual($expiresExpected, $actualExpires);
    }

    public function testLoadFromNestedArchive()
    {
        $imageNestedInsideArchvie = rawurlencode('nested-archive.zip/tests/stub/image.jpeg');
        ob_start();
        $this->client->request('GET', '/archive/'.$imageNestedInsideArchvie);
        ob_get_clean();

        $this->assertResponseIsSuccessful();
    }

    public function testLoadNonExistingArchiveEntryReturn404Error()
    {
        $this->client->request('GET', '/archive/archive.zip/non-exist.jpg');
        self::assertResponseStatusCodeSame(404);
    }

    public function testLoadInvalidImageFromArchiveGiveErrorResponse()
    {
        $this->client->request('GET', '/archive.zip%2Fimage.JPEG');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testSkipPagination()
    {
        $this->client->request('GET', '/archive/Series 1/nopaginate/no-paginate.zip');
        $entry = $this->client->getCrawler()->filter('[data-e2e=entry-link]');
        self::assertCount(120, $entry);
    }
}
