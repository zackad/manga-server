<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssetsControllerTest extends WebTestCase
{
    public function testGetHeaderOfAssetsFile()
    {
        $client = static::createClient();
        $client->request('GET', '/build/app.js');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHasHeader('Content-Type');
        $this->assertSame('application/javascript', $client->getResponse()->headers->get('Content-Type'));
    }
}
