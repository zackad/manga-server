<?php

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
        $this->client->request('GET', '/archive.zip');
        $this->assertResponseIsSuccessful();
    }

    public function testLoadImageFromArchive()
    {
        $this->client->request('GET', '/archive.zip/image.jpeg');

        $expiresExpected = (new \DateTime('+1 week'))->getTimestamp();
        $actualExpires = (new \DateTime($this->client->getResponse()->headers->get('Expires')))->getTimestamp();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'image/jpeg');
        $this->assertLessThanOrEqual($expiresExpected, $actualExpires);
    }

    public function testLoadFromNestedArchive()
    {
        $this->client->request('GET', '/nested-archive.zip%2Ftests%2Fstub%2Fimage.jpeg');

        $this->assertResponseIsSuccessful();
    }

    public function testLoadInvalidImageFromArchiveGiveErrorResponse()
    {
        $this->client->request('GET', '/archive.zip%2Fimage.JPEG');

        $this->assertResponseStatusCodeSame(404);
    }
}
