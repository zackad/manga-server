<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssetsControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testGetHeaderOfAssetsFile()
    {
        $this->client->request('GET', '/build/app.js');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/javascript');
        $this->assertResponseHeaderSame('Expires', (new \DateTime('+1 week'))->format(DATE_RFC7231));
    }

    public function testGetNonExistingFile()
    {
        $this->client->request('GET', '/build/non-existing.json');
        $this->assertResponseStatusCodeSame(404);
    }
}
