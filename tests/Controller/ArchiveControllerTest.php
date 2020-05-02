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
    }

    public function testLoadImageFromArchive()
    {
        $this->client->request('GET', '/archive.zip/image.jpeg');

        $this->assertResponseIsSuccessful();
    }
}
