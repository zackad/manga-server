<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @covers \App\Controller\ArchiveController
 * @covers \App\Service\ArchiveReader
 * @covers \App\Service\ComicBook
 * @covers \App\Service\DirectoryListing
 */
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

    public function testLoadInvalidImageFromArchiveGiveErrorResponse()
    {
        $this->client->request('GET', '/archive.zip%2Fimage.JPEG');

        $this->assertResponseStatusCodeSame(404);
    }
}
