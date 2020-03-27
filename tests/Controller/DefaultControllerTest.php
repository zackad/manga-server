<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider imageProvider
     */
    public function testLoadImage($image)
    {
        $client = static::createClient();
        $client->request('GET', $image);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Expires', (new \DateTime('+1 week'))->format(DATE_RFC7231));
    }

    public function imageProvider()
    {
        return [
            ['/image.jpg'],
            ['/image.jpeg'],
            ['/image.png'],
            ['/image.webp'],
        ];
    }
}
