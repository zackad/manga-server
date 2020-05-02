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

        $expiresHeader = $client->getResponse()->headers->get('expires');
        $expires = (new \DateTime($expiresHeader))->getTimestamp();

        $this->assertResponseIsSuccessful();
        $this->assertLessThanOrEqual((new \DateTime('+1 week'))->getTimestamp(), $expires);
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
