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
        $this->assertResponseHasHeader('Content-Type');
        $this->assertSame('application/javascript', $this->client->getResponse()->headers->get('Content-Type'));
    }

    public function testGetNonExistingFile()
    {
        $this->client->request('GET', '/build/non-existing.json');
        $this->assertResponseStatusCodeSame(404);
    }
}
