<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArchiveControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testListItemsInsideArchiveFile()
    {
        $this->client->request('GET', '/archive.zip');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'text/html; charset=UTF-8');
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
}
