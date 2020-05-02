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

        $expected = json_encode([
            'entries' => [
                'image.jpeg',
                'image.jpg',
                'image.png',
                'image.webp',
            ],
        ]);
        $actual = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertEquals($expected, $actual);
    }

    public function testLoadImageFromArchive()
    {
        $this->client->request('GET', '/archive.zip/image.jpeg');

        $this->assertResponseIsSuccessful();
    }
}
