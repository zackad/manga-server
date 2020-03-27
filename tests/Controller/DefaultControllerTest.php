<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testLoadJpegImage()
    {
        $client = static::createClient();
        $client->request('GET', '/image.jpeg');
        $this->assertResponseIsSuccessful();
    }
}
